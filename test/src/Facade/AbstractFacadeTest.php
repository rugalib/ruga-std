<?php

declare(strict_types=1);

namespace Ruga\Std\Test\Facade;

use Laminas\ServiceManager\ServiceManager;
use Ruga\Std\Facade\AbstractFacade;

/**
 * @author                 Roland Rusch, easy-smart solution GmbH <roland.rusch@easy-smart.ch>
 */
class AbstractFacadeTest extends \Ruga\Std\Test\PHPUnit\AbstractTestSetUp
{
    public function testCanSetContainer(): void
    {
        AbstractFacade::setController($this->getContainer());
        $this->assertInstanceOf(ServiceManager::class, AbstractFacade::getController());
    }
    
    
    
    public function testFaultyFacadeThrowsException(): void
    {
        AbstractFacade::setController($this->getContainer());
        $this->expectException(\RuntimeException::class);
        $this->assertEquals("ERROR", FaultyTestFacade::sayHello());
    }
    
    
    
    public function testCanCallFunctionOfObject(): void
    {
        AbstractFacade::setController($this->getContainer());
        $this->assertInstanceOf(ServiceManager::class, TestFacade::getController());
        
        $this->assertEquals("Hello", TestFacade::sayHello());
    }
    
    
    
    public function testCanGetObject(): void
    {
        AbstractFacade::setController($this->getContainer());
        $this->assertInstanceOf(ServiceManager::class, TestFacade::getController());
        
        $this->assertInstanceOf(TestObject::class, TestFacade::getFacadeInstance());
    }
    
    
    
    public function testCanGetObjectCreatedFromFacade(): void
    {
        $this->assertInstanceOf(TestObject::class, TestFacadeReturningObject::getFacadeInstance());
    }
    
    
    
    /**
     * @return void
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testMustThrowExceptionWhenInstanceNotFound(): void
    {
        $this->expectException(\RuntimeException::class);
        TestDynamicFacade::getFacadeInstance("FOO");
    }
}
