<?php

declare(strict_types=1);

namespace Ruga\Std\Test\Chain;

use Laminas\ServiceManager\ServiceManager;
use Ruga\Std\Chain\AnchorInterface;
use Ruga\Std\Chain\Direction;
use Ruga\Std\Facade\AbstractFacade;

/**
 * @author                 Roland Rusch, easy-smart solution GmbH <roland.rusch@easy-smart.ch>
 */
class ChainTest extends \Ruga\Std\Test\PHPUnit\AbstractTestSetUp
{
    public function testCanCreateAnchor(): void
    {
        $word = new Word();
        $this->assertInstanceOf(AnchorInterface::class, $word);
        
        var_dump($word);
    }
    
    
    
    public function testCanCreateAnchorAndLinkElements(): void
    {
        $word = new Word();
        $this->assertInstanceOf(AnchorInterface::class, $word);
        
        $word->then(new Character('H'))
            ->then(new Character('e'))
            ->then(new Character('l'))
            ->then(new Character('l'))
            ->then(new Character('o'));
        
        echo "{$word->print()}" . PHP_EOL;
        $this->assertEquals('Hello', $word->print());
        
        /** @var Character $o */
        $o = $word->rightEndLink();
        $this->assertInstanceOf(Character::class, $o);
        $this->assertEquals('o', $o->print());
    }
    
    
    
    public function testInsertLinkElementsFirst(): void
    {
        $o = new Character('o');
        echo "Before: {$o->print()}" . PHP_EOL;
        $this->assertEquals('o', $o->print());
        
        $o->first(new Character('l'))
            ->first(new Character('l'))
            ->first(new Character('e'))
            ->first(new Character('H'));
        
        $H = $o->leftEndLink();
        echo "After: {$H->print()}" . PHP_EOL;
        $this->assertEquals('Hello', $H->print());
    }
    
    
    
    public function testLinkPos(): void
    {
        $word = new Word();
        $this->assertInstanceOf(AnchorInterface::class, $word);
        
        $word->then(new Character('H'))
            ->then(new Character('e'))
            ->then(new Character('l'))
            ->then(new Character('l'))
            ->then(new Character('o'));
        
        echo "Word: {$word->print()}" . PHP_EOL;
        $this->assertEquals('Hello', $word->print());
        
        
        $e = $word->nextLink()->nextLink();
        $this->assertInstanceOf(Character::class, $e);
        $this->assertEquals('ello', $e->print());
        
        echo "linkPos(): {$e->linkPos()}" . PHP_EOL;
        $this->assertEquals(2, $e->linkPos());
    }
    
    
    
    public function testInsertAfter(): void
    {
        $word = new Word();
        $this->assertInstanceOf(AnchorInterface::class, $word);
        
        $word->then(new Character('H'))
            ->then(new Character('e'))
            ->then(new Character('l'))
            ->then(new Character('o'));
        
        echo "Before: {$word->print()}" . PHP_EOL;
        $this->assertEquals('Helo', $word->print());
        
        
        $e = $word->nextLink()->nextLink();
        $this->assertInstanceOf(Character::class, $e);
        $this->assertEquals('elo', $e->print());
        
        $e->insertAfter(new Character('l'));
        
        echo "After: {$word->print()}" . PHP_EOL;
        $this->assertEquals('Hello', $word->print());
    }
    
    
    
    public function testInsertBefore(): void
    {
        $word = new Word();
        $this->assertInstanceOf(AnchorInterface::class, $word);
        
        $word->then(new Character('H'))
            ->then(new Character('e'))
            ->then(new Character('l'))
            ->then(new Character('o'));
        
        echo "Before: {$word->print()}" . PHP_EOL;
        $this->assertEquals('Helo', $word->print());
        
        
        $l = $word->nextLink()->nextLink()->nextLink();
        $this->assertInstanceOf(Character::class, $l);
        $this->assertEquals('lo', $l->print());
        
        $l->insertBefore(new Character('l'));
        
        echo "After: {$word->print()}" . PHP_EOL;
        $this->assertEquals('Hello', $word->print());
    }
    
    
    public function testGetChainAsArray(): void
    {
        $H = (new Character('H'))
            ->then(new Character('e'))
            ->then(new Character('l'))
            ->then(new Character('l'))
            ->then(new Character('o'));
        
        echo "Word: {$H->print()}" . PHP_EOL;
        $this->assertEquals('Hello', $H->print());
        
        
        $a=$H->getChainLinksAsArray();
        $this->assertIsArray($a);
        $this->assertCount(5, $a);
        
        $str='';
        foreach($a as $character) {
            $str.=$character->getCharacter();
        }
        echo "Forward: {$str}" . PHP_EOL;
        $this->assertEquals('Hello', $str);
        unset($a);
    
        
        
        $a=$H->rightEndLink()->getChainLinksAsArray(Direction::BACKWARD());
        $this->assertIsArray($a);
        $this->assertCount(5, $a);
    
        $str='';
        foreach($a as $character) {
            $str.=$character->getCharacter();
        }
        echo "Backward: {$str}" . PHP_EOL;
        $this->assertEquals('olleH', $str);
        unset($a);

    }
    
    
}
