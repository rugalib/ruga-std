<?php
/*
 * SPDX-FileCopyrightText: 2023 Roland Rusch, easy-smart solution GmbH <roland.rusch@easy-smart.ch>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

declare(strict_types=1);

namespace Ruga\Std\Test\Facade;


/**
 * Facade creates object by consulting given controller.
 *
 * @author   Roland Rusch, easy-smart solution GmbH <roland.rusch@easy-smart.ch>
 *
 */
abstract class TestFacade extends \Ruga\Std\Facade\AbstractFacade
{
    protected static function getFacadeInstanceName(): string
    {
        return TestObject::class;
    }
}
