<?php
/*
 * SPDX-FileCopyrightText: 2023 Roland Rusch, easy-smart solution GmbH <roland.rusch@easy-smart.ch>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

declare(strict_types=1);


namespace Ruga\Std\Test\Facade;


/**
 * Facade creates object by trying to resolve given name/object.
 *
 * @author   Roland Rusch, easy-smart solution GmbH <roland.rusch@easy-smart.ch>
 *
 */
abstract class TestDynamicFacade extends \Ruga\Std\Facade\AbstractFacade
{
    public static function getFacadeInstance($name = null)
    {
        return static::resolveFacadeInstance($name);
    }
}
