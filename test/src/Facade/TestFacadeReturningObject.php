<?php
declare(strict_types=1);

namespace Ruga\Std\Test\Facade;


/**
 * In this example, our object will be created directly by the facade. This does not require
 * a controller.
 *
 * @author   Roland Rusch, easy-smart solution GmbH <roland.rusch@easy-smart.ch>
 */
abstract class TestFacadeReturningObject extends \Ruga\Std\Facade\AbstractFacade
{
    
    public static function getFacadeInstance()
    {
        return self::resolveFacadeInstance(new TestObject());
    }
    
}
