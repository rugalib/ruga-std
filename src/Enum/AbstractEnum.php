<?php
declare(strict_types=1);

namespace Ruga\Std\Enum;

use ArrayIterator;
use ReflectionClass;
use ReflectionException;
use stdClass;

/**
 * Abtract for an enum used for type hinting/safety.
 *
 * @author   Roland Rusch, easy-smart solution GmbH <roland.rusch@easy-smart.ch>
 *
 */
abstract class AbstractEnum implements EnumInterface
{
    /** @var array Cache for constant name => value pairs */
    private static $cacheConstVal = [];
    
    /** @var array Cache for constant value => full name pairs */
    private static $cacheValFullname = [];
    
    /** @var mixed The value when used as an instance. */
    private $instanceValue;
    
    /** @var array Constant value => full name */
    const __fullnameMap = [];
    
    /** @var array Constant value => extra data */
    const __extraMap = [];
    
    /** @var string The class to use as template for getObjects() */
    const __objectClass = StdObjectClass::class;
    
    
    
    /**
     * Return an array containing all constant names.
     *
     * @return array
     * @throws ReflectionException
     */
    public static function getConstants(): array
    {
        $calledClass = get_called_class();
        if (!array_key_exists($calledClass, self::$cacheConstVal)) {
            $reflect = new ReflectionClass($calledClass);
            foreach ($reflect->getConstants() as $constName => $constValue) {
                if (substr($constName, 0, 2) != '__') {
                    self::$cacheConstVal[$calledClass][$constName] = $constValue;
                }
            }
        }
        return self::$cacheConstVal[$calledClass];
    }
    
    
    
    /**
     * Check if the name is valid for the enum.
     *
     * @param string $name   Name to check
     * @param bool   $strict Ignore case if true
     *
     * @return bool
     * @throws ReflectionException
     */
    public static function isValidName($name, bool $strict = true): bool
    {
        $constants = static::getConstants();
        
        if ($strict) {
            return array_key_exists($name, $constants);
        }
        
        $keys = array_map('strtolower', array_keys($constants));
        return in_array(strtolower($name), $keys);
    }
    
    
    
    /**
     * Check if the value is valid for the enum.
     *
     * @param AbstractEnum|mixed $value  Value to check
     * @param bool               $strict Also check type if true
     *
     * @return bool
     * @throws ReflectionException
     */
    public static function isValidValue($value, bool $strict = true): bool
    {
        if (is_a($value, self::class)) {
            $value = $value->getValue();
        }
        return in_array($value, array_values(static::getConstants()), $strict);
    }
    
    
    
    /**
     * Returns the name of the constant for the given value.
     *
     * @param AbstractEnum|mixed $value
     *
     * @return string
     * @throws ReflectionException
     */
    public static function getConstantName($value = null): string
    {
        if (is_a($value, self::class)) {
            $value = $value->getValue();
        }
        if (!static::isValidValue($value)) {
            throw new Exception\OutOfRangeException("'{$value}' is not a valid value for " . get_called_class() . ".");
        }
        return array_flip(static::getConstants())[$value] ?? '';
    }
    
    
    
    /**
     * Returns the value of the constant.
     *
     * @param string $name
     *
     * @return mixed|null
     * @throws ReflectionException
     */
    public static function getConstantValue(string $name)
    {
        if (!static::isValidName($name)) {
            throw new Exception\OutOfRangeException(
                "'{$name}' is not a valid constant name for " . get_called_class() . "."
            );
        }
        return static::getConstants()[$name] ?? null;
    }
    
    
    
    /**
     * Returns the full name (from self::__fullnameMap) for the constant value.
     *
     * @param AbstractEnum|mixed $value
     *
     * @return string
     * @throws ReflectionException
     */
    public static function getFullname($value): string
    {
        if (is_a($value, self::class)) {
            $value = $value->getValue();
        }
        if (!static::isValidValue($value)) {
            throw new Exception\OutOfRangeException("'{$value}' is not a valid value for " . get_called_class() . ".");
        }
        return static::__fullnameMap[$value] ?? static::getConstantName($value);
    }
    
    
    
    /**
     * Return the constant value => fullname pairs for use in
     * select multioptions.
     *
     * @return array
     * @throws ReflectionException
     */
    public static function getMultiOptions(): array
    {
        $calledClass = get_called_class();
        if (!array_key_exists($calledClass, self::$cacheValFullname)) {
            self::$cacheValFullname[$calledClass] = [];
            foreach (static::getConstants() as $constName => $constVal) {
                @self::$cacheValFullname[$calledClass][$constVal] = static::getFullname($constVal);
            }
        }
        return self::$cacheValFullname[$calledClass];
    }
    
    
    
    /**
     * Return an array containing all the constants as objects.
     * Attributes from static::_extraMap are added to the objects.
     *
     * @param null $marker Marker string or array to filter returned objects.
     *
     * @param bool $all    true=also return non-matching objects.
     *
     * @return ArrayIterator
     * @throws ReflectionException
     */
    public static function getObjects($marker = null, $all = false): ArrayIterator
    {
        $a = [];
        $extrafields = [];
        
        if (!is_a(static::__objectClass, StdObjectClassInterface::class, true)) {
            throw new Exception\InvalidObjectClassException(
                static::__objectClass . " (static::__objectClass) must implement " . StdObjectClassInterface::class
            );
        }
        
        $constants = static::getConstants();
        foreach ($constants as $constName => $constVal) {
            $o = (new ReflectionClass(static::__objectClass))->newInstance();
            $o->fullname = static::getFullname($constVal);
            $o->id = $constName;
            $o->val = $constVal;
            $o->mark = true;
            
            foreach ((static::__extraMap[$constVal] ?? []) as $key => $val) {
                if (!in_array($key, $extrafields)) {
                    $extrafields[] = $key;
                }
                $o->$key = $val;
            }
            
            $a[] = $o;
        }
        
        foreach ($a as $key => $val) {
            foreach ($extrafields as $fk) {
                if (!isset($val->$fk)) {
                    $val->$fk = null;
                }
            }
        }
        
        if (!empty($marker)) {
            if (!is_array($marker)) {
                $marker = [$marker];
            }
            foreach ($a as $key => $val) {
                if (is_array($val->markers)) {
                    $val->mark = (count(array_intersect($marker, $val->markers)) > 0);
                } else {
                    $val->mark = true;
                }
            }
        }
        
        if (!$all) {
            foreach ($a as $key => $object) {
                if (!$object->mark) {
                    unset($a[$key]);
                }
            }
        }
        return new ArrayIterator($a);
    }
    
    
    
    /**
     * Returns the objectClass-object for the given value.
     * Unlike getObjects() this returns only one object and can not handle markers. Instead it creates the object by
     * the given value.
     *
     * @param mixed $value
     *
     * @return object
     * @throws ReflectionException
     */
    public static function getObject($value)
    {
        if (is_a($value, self::class)) {
            $value = $value->getValue();
        }
        if (!static::isValidValue($value)) {
            throw new Exception\OutOfRangeException("'{$value}' is not a valid value for " . get_called_class() . ".");
        }
        
        if (!is_a(static::__objectClass, StdObjectClassInterface::class, true)) {
            throw new Exception\InvalidObjectClassException(
                static::__objectClass . " (static::__objectClass) must implement " . StdObjectClassInterface::class
            );
        }
        
        $o = (new ReflectionClass(static::__objectClass))->newInstance();
        $o->fullname = static::getFullname($value);
        $o->id = static::getConstantName($value);
        $o->val = $value;
        $o->mark = false;
        return $o;
    }
    
    
    
    /**
     * If the enum is used as type hint, the user has to pass an instance.
     * An instance can be created by calling a constant as static function.
     * MyEnum::MYCONST()
     *
     * @param AbstractEnum|mixed $value
     *
     * @throws ReflectionException
     */
    final public function __construct($value)
    {
        $this->setInstanceValue($value);
    }
    
    
    
    /**
     * Returns the name of the constant, when dealing with an instance.
     *
     * @return string
     * @throws ReflectionException
     */
    public function getName(): string
    {
        return static::getConstantName($this->getInstanceValue());
    }
    
    
    
    /**
     * Returns the value of the constant, when dealing with an instance.
     *
     * @return mixed
     */
    public function getValue()
    {
        return $this->getInstanceValue();
    }
    
    
    
    /**
     * Returns the fullname of the instance.
     *
     * @return string
     * @throws ReflectionException
     */
    public function getFull(): string
    {
        return static::getFullname($this->getValue());
    }
    
    
    
    /**
     * Save the value of the current enum instance.
     *
     * @param mixed $value Value for the enum instance
     *
     * @throws ReflectionException
     */
    final private function setInstanceValue($value)
    {
        if (is_a($value, self::class)) {
            $value = $value->getValue();
        }
        if (!static::isValidValue($value)) {
            throw new Exception\OutOfRangeException("'{$value}' is not a valid value for " . get_called_class() . ".");
        }
        
        $this->instanceValue = $value;
    }
    
    
    
    /**
     * Return the value of the current enum instance.
     *
     * @return mixed
     */
    final private function getInstanceValue()
    {
        return $this->instanceValue;
    }
    
    
    
    /**
     * Called, when the user calls an unknown static function of \AbstractEnum.
     *
     * @param string $name      Name of the called static function
     * @param array  $arguments Arguments for the static function
     *
     * @return static
     * @throws ReflectionException
     */
    public static function __callStatic($name, $arguments)
    {
        if (!static::isValidName($name)) {
            throw new Exception\OutOfRangeException(
                "'{$name}' is not a valid constant name for " . get_called_class() . "."
            );
        } else {
            return new static(static::getConstantValue($name));
        }
    }
    
    
    
    /**
     * Called, when the user uses the object as function.
     * $enum()
     *
     * @return mixed
     */
    public function __invoke()
    {
        return $this->getValue();
    }
    
    
    
    /**
     * Called, when the user uses the object as variable.
     * $enum
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getValue();
    }
}