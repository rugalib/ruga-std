<?php

namespace Ruga\Std\Test\Chain;

use Ruga\Std\Chain\LinkInterface;
use Ruga\Std\Chain\LinkTrait;

class Character implements LinkInterface
{
    use LinkTrait;
    
    private string $char;
    
    
    
    public function __construct(string $char)
    {
        $this->char=$char;
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