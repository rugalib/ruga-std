<?php
/*
 * SPDX-FileCopyrightText: 2023 Roland Rusch, easy-smart solution GmbH <roland.rusch@easy-smart.ch>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

declare(strict_types=1);

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