<?php
/*
 * SPDX-FileCopyrightText: 2023 Roland Rusch, easy-smart solution GmbH <roland.rusch@easy-smart.ch>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

declare(strict_types=1);

namespace Ruga\Std\Test\Chain;

use Ruga\Std\Chain\AnchorInterface;
use Ruga\Std\Chain\LinkTrait;

class Word implements AnchorInterface
{
    use LinkTrait;
    
    /**
     * Return the word.
     *
     * @return string
     */
    public function print(): string
    {
        return ($this->nextLink() ? $this->nextLink()->print() : '');
    }
}