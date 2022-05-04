<?php

namespace Ruga\Std\Chain;

/**
 * @method static self FORWARD()
 * @method static self BACKWARD()
 */
final class Direction extends \Ruga\Std\Enum\AbstractEnum
{
    const FORWARD = 1;
    const BACKWARD = -1;
    
    const __default = self::FORWARD;
}