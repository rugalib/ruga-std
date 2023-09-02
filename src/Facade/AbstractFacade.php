<?php
/*
 * SPDX-FileCopyrightText: 2023 Roland Rusch, easy-smart solution GmbH <roland.rusch@easy-smart.ch>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

declare(strict_types=1);

namespace Ruga\Std\Facade;

use Psr\Container\ContainerInterface;

/**
 * This facade class can be used to implement the facade design pattern. The container
 * must be set once by calling setController().
 * Classes which inherit AbstractFacade can then return the object behind the facade by calling
 * ::getFacadeInstance().
 * It is also possibe to call individual functions of the instance by calling them as static
 * function of the facade like ::functionOfTheObjectBehindTheFacade().
 *
 * @author   Roland Rusch, easy-smart solution GmbH <roland.rusch@easy-smart.ch>
 */
abstract class AbstractFacade
{
    /** @var ContainerInterface */
    protected static $controller;
    
    /** @var array */
    protected static $instanceCache;
    
    
    
    /**
     * Set the application controller.
     *
     * @param ContainerInterface $controller
     *
     * @return void
     */
    public static function setController(ContainerInterface $controller): void
    {
        static::$controller = $controller;
    }
    
    
    
    /**
     * Get the stored application controller.
     *
     * @return ContainerInterface
     */
    public static function getController(): ContainerInterface
    {
        return static::$controller;
    }
    
    
    
    /**
     * Get the name of the component for the concrete facade.
     * Override this function in every concrete facade.
     *
     * @return string
     *
     * @throws \RuntimeException
     */
    protected static function getFacadeInstanceName(): string
    {
        throw new \RuntimeException('Facade must implement getFacadeInstanceName()');
    }
    
    
    
    /**
     * Resolve the facade root instance from the container.
     *
     * @param string|object $name
     *
     * @return object
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    protected static function resolveFacadeInstance($name): object
    {
        if (is_object($name)) {
            return $name;
        }
        
        if (isset(static::$instanceCache[$name])) {
            return static::$instanceCache[$name];
        }
        
        if (static::$controller) {
            return static::$instanceCache[$name] = static::$controller->get($name);
        }
        
        throw new \RuntimeException('No facade instance found');
    }
    
    
    
    /**
     * Get the object instance behind the facade.
     *
     * @return mixed
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public static function getFacadeInstance()
    {
        return static::resolveFacadeInstance(static::getFacadeInstanceName());
    }
    
    
    
    /**
     * Handle static calls to the facade.
     *
     * @param string $method
     * @param array  $args
     *
     * @return mixed
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public static function __callStatic($method, $args)
    {
        $instance = static::getFacadeInstance();
        
        if (!$instance) {
            throw new \RuntimeException('No facade instance found');
        }
        
        return $instance->$method(...$args);
    }
}