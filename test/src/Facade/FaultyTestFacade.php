<?php
/*
 * SPDX-FileCopyrightText: 2023 Roland Rusch, easy-smart solution GmbH <roland.rusch@easy-smart.ch>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

declare(strict_types=1);

namespace Ruga\Std\Test\Facade;


/**
 * Faulty facade. Object class name is missing.
 *
 * @author   Roland Rusch, easy-smart solution GmbH <roland.rusch@easy-smart.ch>
 */
class FaultyTestFacade extends \Ruga\Std\Facade\AbstractFacade
{
//    protected static function getFacadeInstanceName(): string
//    {
//        return AuthenticationServiceInterface::class;
//    }
}
