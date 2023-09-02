<?php
/*
 * SPDX-FileCopyrightText: 2023 Roland Rusch, easy-smart solution GmbH <roland.rusch@easy-smart.ch>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

declare(strict_types=1);

namespace Ruga\Std\Test\Enum;

use Ruga\Std\Enum\AbstractEnum;
use PHPUnit\Framework\TestCase;

/**
 * @author   Roland Rusch, easy-smart solution GmbH <roland.rusch@easy-smart.ch>
 */
final class AbstractEnumTest extends \Ruga\Std\Test\PHPUnit\AbstractTestSetUp
{
    public function testCanCreateObject(): void
    {
        // Usual object creation
        $this->assertInstanceOf(AbstractEnum::class, new \Ruga\Std\Test\Enum\Weekday('mon'));
        $this->assertInstanceOf(AbstractEnum::class, new \Ruga\Std\Test\Enum\Weekday(Weekday::MONDAY));
        
        // Expect exception, when wrong value name is used
        $this->expectException(\Ruga\Std\Enum\Exception\OutOfRangeException::class);
        $this->assertInstanceOf(AbstractEnum::class, new \Ruga\Std\Test\Enum\Weekday('foo'));
        
        
        // Shorthand object creation using __callStatic
        $this->assertInstanceOf(AbstractEnum::class, \Ruga\Std\Test\Enum\Weekday::MONDAY());
        
        // Expect exception, when wrong constant name is used
        $this->expectException(\Ruga\Std\Enum\Exception\OutOfRangeException::class);
        $this->assertInstanceOf(AbstractEnum::class, \Ruga\Std\Test\Enum\Weekday::FOO());
    }
    
    
    
    public function testCanGetConstants()
    {
        $constants = \Ruga\Std\Test\Enum\Weekday::getConstants();
        print_r($constants);
        $this->assertIsArray($constants);
        $this->assertGreaterThan(0, count($constants));
    }
    
    
    
    public function testCanCheckValidConstant()
    {
        $this->assertFalse(\Ruga\Std\Test\Enum\Weekday::isValidName('GUGUS'), 'GUGUS');
        $this->assertTrue(\Ruga\Std\Test\Enum\Weekday::isValidName('FRIDAY'), 'FRIDAY');
        $this->assertFalse(\Ruga\Std\Test\Enum\Weekday::isValidName('friday'), 'friday');
        
        $this->assertFalse(\Ruga\Std\Test\Enum\Weekday::isValidName('GUGUS', false), 'GUGUS non-strict');
        $this->assertTrue(\Ruga\Std\Test\Enum\Weekday::isValidName('FRIDAY', false), 'FRIDAY non-strict');
        $this->assertTrue(\Ruga\Std\Test\Enum\Weekday::isValidName('friday', false), 'friday non-strict');
    }
    
    
    
    public function testCanCheckValidValue()
    {
        $this->assertFalse(\Ruga\Std\Test\Enum\Weekday::isValidValue('foo'), 'foo');
        $this->assertTrue(\Ruga\Std\Test\Enum\Weekday::isValidValue('fri'), 'fri');
        $this->assertFalse(\Ruga\Std\Test\Enum\Weekday::isValidValue('Fri'), 'Fri');
        $this->assertTrue(\Ruga\Std\Test\Enum\Weekday::isValidValue(\Ruga\Std\Test\Enum\Weekday::MONDAY()), 'MONDAY()');
        
        $this->assertFalse(\Ruga\Std\Test\Enum\Weekday::isValidValue('foo', false), 'foo non-strict');
        $this->assertTrue(\Ruga\Std\Test\Enum\Weekday::isValidValue('fri', false), 'fri non-strict');
        $this->assertFalse(\Ruga\Std\Test\Enum\Weekday::isValidValue('Fri', false), 'Fri non-strict');
        $this->assertTrue(
            \Ruga\Std\Test\Enum\Weekday::isValidValue(\Ruga\Std\Test\Enum\Weekday::MONDAY(), false),
            'MONDAY() non-strict'
        );
    }
    
    
    
    public function testCanGetConstantNameFromValue()
    {
        $this->assertSame('FRIDAY', \Ruga\Std\Test\Enum\Weekday::getConstantName('fri'));
        $this->assertSame(
            'TUESDAY',
            \Ruga\Std\Test\Enum\Weekday::getConstantName(\Ruga\Std\Test\Enum\Weekday::TUESDAY())
        );
        $this->expectException(\Ruga\Std\Enum\Exception\OutOfRangeException::class);
        \Ruga\Std\Test\Enum\Weekday::getConstantName('foo');
    }
    
    
    
    public function testCanGetConstantValueFromName()
    {
        $this->assertSame('fri', \Ruga\Std\Test\Enum\Weekday::getConstantValue('FRIDAY'));
        $this->expectException(\Ruga\Std\Enum\Exception\OutOfRangeException::class);
        \Ruga\Std\Test\Enum\Weekday::getConstantValue('foo');
    }
    
    
    
    public function testCanGetFullnameFromValue()
    {
        $this->assertSame(
            'Wednesday',
            \Ruga\Std\Test\Enum\Weekday::getFullname(\Ruga\Std\Test\Enum\Weekday::WEDNESDAY)
        );
        $this->assertSame(
            'Wednesday',
            \Ruga\Std\Test\Enum\Weekday::getFullname(\Ruga\Std\Test\Enum\Weekday::WEDNESDAY())
        );
        // Enum returns the constant name if no fullname is found for the value
        $this->assertSame('THURSDAY', \Ruga\Std\Test\Enum\Weekday::getFullname(\Ruga\Std\Test\Enum\Weekday::THURSDAY));
        
        $this->expectException(\Ruga\Std\Enum\Exception\OutOfRangeException::class);
        \Ruga\Std\Test\Enum\Weekday::getFullname('foo');
    }
    
    
    
    public function testCanGetMultioptions()
    {
        $o = \Ruga\Std\Test\Enum\Weekday::getMultiOptions();
        $this->assertIsArray($o);
        $this->assertSame('TUESDAY', $o['tue']);
        $this->assertSame('Wednesday', $o['wed']);
    }
    
    
    
    public function testCanGetObjects()
    {
        $o = \Ruga\Std\Test\Enum\Weekday::getObjects();
        $this->assertInstanceOf(\ArrayIterator::class, $o);
        $this->assertSame(8, count($o));
        /** @var \Ruga\Std\Test\Enum\WeekdayObject $o1 */
        $o1 = $o->current();
        $this->assertInstanceOf(\Ruga\Std\Test\Enum\WeekdayObject::class, $o1);
        $this->assertSame('MONDAY', $o1->fullname);
        $this->assertSame('MONDAY', $o1->id);
        $this->assertSame('mon', $o1->val);
        $this->assertSame(true, $o1->mark);
    }
    
    
    
    public function testCanGetObjectsByMarker()
    {
        $o = \Ruga\Std\Test\Enum\Weekday::getObjects('WEEKEND');
        $this->assertInstanceOf(\ArrayIterator::class, $o);
        $this->assertSame(2, count($o));
        /** @var \Ruga\Std\Test\Enum\WeekdayObject $o1 */
        $o1 = $o->current();
        $this->assertInstanceOf(\Ruga\Std\Test\Enum\WeekdayObject::class, $o1);
        $this->assertSame('SATURDAY', $o1->fullname);
        $this->assertSame('SATURDAY', $o1->id);
        $this->assertSame('sat', $o1->val);
        $this->assertSame(true, $o1->mark);
        
        $o = \Ruga\Std\Test\Enum\Weekday::getObjects('WEEKEND', true);
        $this->assertInstanceOf(\ArrayIterator::class, $o);
        $this->assertSame(8, count($o));
        /** @var \Ruga\Std\Test\Enum\WeekdayObject $o1 */
        $o1 = $o->current();
        $this->assertInstanceOf(\Ruga\Std\Test\Enum\WeekdayObject::class, $o1);
        $this->assertSame('MONDAY', $o1->fullname);
        $this->assertSame('MONDAY', $o1->id);
        $this->assertSame('mon', $o1->val);
        $this->assertSame(false, $o1->mark);
    }
    
    
    
    public function testCanGetObjectsByMultipleMarkers()
    {
        $o = \Ruga\Std\Test\Enum\Weekday::getObjects(['WEEKEND', 'DAYSWITHF']);
        $this->assertInstanceOf(\ArrayIterator::class, $o);
        $this->assertSame(3, count($o));
        /** @var \Ruga\Std\Test\Enum\WeekdayObject $o1 */
        $o1 = $o->current();
        $this->assertInstanceOf(\Ruga\Std\Test\Enum\WeekdayObject::class, $o1);
        $this->assertSame('FRIDAY', $o1->fullname);
        $this->assertSame('FRIDAY', $o1->id);
        $this->assertSame('fri', $o1->val);
        $this->assertSame(true, $o1->mark);
        
        $o = \Ruga\Std\Test\Enum\Weekday::getObjects(['WEEKEND', 'DAYSWITHF'], true);
        $this->assertInstanceOf(\ArrayIterator::class, $o);
        $this->assertSame(8, count($o));
        /** @var \Ruga\Std\Test\Enum\WeekdayObject $o1 */
        $o1 = $o->current();
        $this->assertInstanceOf(\Ruga\Std\Test\Enum\WeekdayObject::class, $o1);
        $this->assertSame('MONDAY', $o1->fullname);
        $this->assertSame('MONDAY', $o1->id);
        $this->assertSame('mon', $o1->val);
        $this->assertSame(false, $o1->mark);
    }
    
    
    
    public function testCanGetOneObject()
    {
        /** @var \Ruga\Std\Test\Enum\WeekdayObject $o1 */
        $o1 = \Ruga\Std\Test\Enum\Weekday::getObject('wed');
        $this->assertInstanceOf(\Ruga\Std\Test\Enum\WeekdayObject::class, $o1);
        $this->assertSame('Wednesday', $o1->fullname);
        $this->assertSame('WEDNESDAY', $o1->id);
        $this->assertSame('wed', $o1->val);
        $this->assertSame(false, $o1->mark);
        var_export($o1);
    }
    
    
    
    public function testCanPrintValue()
    {
        $weekday = \Ruga\Std\Test\Enum\Weekday::WEDNESDAY();
        $this->assertInstanceOf(\Ruga\Std\Test\Enum\Weekday::class, $weekday);
        $this->assertSame('wed', $weekday());
        $this->assertSame('wed', "{$weekday}");
        $this->assertSame('wed', $weekday->getValue());
        $this->assertSame('WEDNESDAY', $weekday->getName());
        $this->assertSame('WEDNESDAY', $weekday::getConstantName($weekday()));
        $this->assertSame('Wednesday', $weekday->getFull());
    }
    
    
    
    public function testCanTestWithIs()
    {
        $weekday = \Ruga\Std\Test\Enum\Weekday::WEDNESDAY();
        $this->assertInstanceOf(\Ruga\Std\Test\Enum\Weekday::class, $weekday);
        
        $this->assertTrue($weekday->isValue(Weekday::WEDNESDAY()));
        $this->assertTrue($weekday->isValue(Weekday::WEDNESDAY));
        $this->assertTrue($weekday->isName('WEDNESDAY'));
        $this->assertTrue($weekday->isWEDNESDAY());
    }
    
    
    
    public function testCanCreateInstanceWithDefault()
    {
        $weekday = new Weekday();
        $this->assertInstanceOf(\Ruga\Std\Test\Enum\Weekday::class, $weekday);
        $this->assertTrue($weekday->isUNDEF());
    }
    
}
