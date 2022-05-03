<?php
declare(strict_types=1);

namespace Ruga\Std\Test\Enum;


/**
 * An enum of all the weekdays.
 *
 * @method static self MONDAY()
 * @method static self TUESDAY()
 * @method static self WEDNESDAY()
 * @method static self THURSDAY()
 * @method static self FRIDAY()
 * @method static self SATURDAY()
 * @method static self SUNDAY()
 * @method static self UNDEF()
 */
final class Weekday extends \Ruga\Std\Enum\AbstractEnum
{
    const MONDAY = 'mon';
    const TUESDAY = 'tue';
    const WEDNESDAY = 'wed';
    const THURSDAY = 'thu';
    const FRIDAY = 'fri';
    const SATURDAY = 'sat';
    const SUNDAY = 'sun';
    
    const UNDEF = 'unknown';
    
    const __default = self::UNDEF;
    const __objectClass = WeekdayObject::class;
    const __fullnameMap = [
        self::WEDNESDAY => 'Wednesday',
    ];
    const __extraMap = [
        self::MONDAY => ['isWorkday' => true, 'weekStart' => true, 'markers' => []],
        self::TUESDAY => ['isWorkday' => true, 'markers' => []],
        self::WEDNESDAY => ['isWorkday' => true, 'markers' => []],
        self::THURSDAY => ['isWorkday' => true, 'markers' => []],
        self::FRIDAY => ['isWorkday' => true, 'markers' => ['DAYSWITHF']],
        self::SATURDAY => ['isWorkday' => false, 'markers' => ['WEEKEND']],
        self::SUNDAY => ['isWorkday' => false, 'markers' => ['WEEKEND']],
        self::UNDEF => ['markers' => []],
    ];
}
