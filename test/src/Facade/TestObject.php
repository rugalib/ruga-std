<?php
declare(strict_types=1);

namespace Ruga\Std\Test\Facade;

/**
 * Test object.
 *
 * @author   Roland Rusch, easy-smart solution GmbH <roland.rusch@easy-smart.ch>
 */
class TestObject
{
    public function sayHello(): string
    {
        return "Hello";
    }
}