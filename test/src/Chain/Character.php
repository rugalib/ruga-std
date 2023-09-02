<?php
/*
 * SPDX-FileCopyrightText: 2023 Roland Rusch, easy-smart solution GmbH <roland.rusch@easy-smart.ch>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

declare(strict_types=1);

namespace Ruga\Std\Test\Chain;

use Ruga\Std\Chain\LinkInterface;
use Ruga\Std\Chain\LinkTrait;

class Character implements LinkInterface
{
    use LinkTrait;
    
    private string $char;
    
    
    
    public function __construct(string $char)
    {
        $this->char = $char;
    }
    
    
    
    public function getCharacter(): string
    {
        return $this->char;
    }
    
    
    
    /**
     * Return the character.
     *
     * @return string
     */
    public function print(): string
    {
        return $this->getCharacter() . ($this->nextLink() ? $this->nextLink()->print() : '');
    }
    
    
}