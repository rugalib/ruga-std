<?php
declare(strict_types=1);

namespace Ruga\Std\Enum;

use ArrayIterator;

/**
 * Interface to the enum class.
 *
 * @see      AbstractEnum
 * @author   Roland Rusch, easy-smart solution GmbH <roland.rusch@easy-smart.ch>
 *
 */
interface EnumInterface
{
    public static function getConstants(): array;
    
    
    
    public static function isValidName($name, bool $strict = false): bool;
    
    
    
    public static function isValidValue($value, bool $strict = true): bool;
    
    
    
    public static function getConstantName($value): string;
    
    
    
    public static function getConstantValue(string $name);
    
    
    
    public static function getFullname($value): string;
    
    
    
    public static function getMultiOptions(): array;
    
    
    
    public static function getObjects($marker = null): ArrayIterator;
    
    
    
    public static function getObject($value);
    
    
    
    public function getName(): string;
    
    
    
    public function getValue();
    
    
    
    public function getFull(): string;
}
