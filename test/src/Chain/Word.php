<?php

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