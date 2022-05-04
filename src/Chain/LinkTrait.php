<?php
declare(strict_types=1);

namespace Ruga\Std\Chain;


/**
 * Provides functions for a nameable template.
 *
 * @see      LinkInterface
 * @author   Roland Rusch, easy-smart solution GmbH <roland.rusch@easy-smart.ch>
 */
trait LinkTrait
{
    /**
     * Pointer to prev link in chain.
     *
     * @var LinkInterface
     */
    private $linkTrait_prevLink = null;
    
    
    /**
     * Pointer to next link in chain.
     *
     * @var LinkInterface
     */
    private $linkTrait_nextLink = null;
    
    
    
    /**
     * Add a chain link to the end of the chain.
     *
     * @param LinkInterface $link Link element to add
     *
     * @return LinkInterface Current element
     */
    public function then(LinkInterface $link): LinkInterface
    {
        if (!$this->nextLink()) {
            $this->nextLink($link);
            $link->prevLink($this);
        } else {
            $this->nextLink()->then($link);
        }
        return $this;
    }
    
    
    
    /**
     * Add a chain link to the beginning of the chain.
     *
     * @param LinkInterface $link Link element to add
     *
     * @return LinkInterface Current element
     */
    public function first(LinkInterface $link): LinkInterface
    {
        if (!$this->prevLink()) {
            $this->prevLink($link);
            $link->nextLink($this);
        } else {
            $this->prevLink()->first($link);
        }
        return $this;
    }
    
    
    /**
     * Add a chain link before the current object.
     *
     * @param LinkInterface $newlink Link element to add
     *
     * @return LinkInterface Current element
     */
    function insertBefore(LinkInterface $newlink): LinkInterface
    {
        // Add new link to current link
        $oldprev=$this->prevLink($newlink);
        // Connect new link to current link
        $newlink->nextLink($this);
        // Connect new link to old link
        $newlink->prevLink($oldprev);
        // Connect old prev back to new link
        $oldprev->nextLink($newlink);
        return $this;
    }
    
    
    
    /**
     * Add a chain link after the current object.
     *
     * @param LinkInterface $newlink Link element to add
     *
     * @return LinkInterface Current element
     */
    function insertAfter(LinkInterface $newlink): LinkInterface
    {
        // Add new link to current link
        $oldnext=$this->nextLink($newlink);
        // Connect new link back to current link
        $newlink->prevLink($this);
        // Connect new link to old link
        $newlink->nextLink($oldnext);
        // Connect old next back to new link
        $oldnext->prevLink($newlink);
        return $this;
    }
    
    
    
    /**
     * Returns the left-most link element.
     *
     * @return LinkInterface
     */
    function leftEndLink(): LinkInterface
    {
        if ($this->prevLink()) {
            return $this->prevLink()->leftEndLink();
        }
        return $this;
    }
    
    
    
    /**
     * Returns the right-most link element.
     *
     * @return LinkInterface
     */
    function rightEndLink(): LinkInterface
    {
        if ($this->nextLink()) {
            return $this->nextLink()->rightEndLink();
        }
        return $this;
    }
    
    
    
    /**
     * Return the next link object and set a new next link object if $newnext is provided.
     * Take care! This does not insert link elements into the chain.
     *
     * @param LinkInterface|null $newnext
     *
     * @return LinkInterface
     */
    public function nextLink(LinkInterface $newnext = null): ?LinkInterface
    {
        $oldnext = $this->linkTrait_nextLink;
        if ($newnext) {
            $this->linkTrait_nextLink = $newnext;
        }
        return $oldnext;
    }
    
    
    
    /**
     * Return the previous link object and set a new previous link object if $newprev is provided.
     * Take care! This does not insert link elements into the chain.
     *
     * @param LinkInterface|null $newprev
     *
     * @return LinkInterface
     */
    public function prevLink(LinkInterface $newprev = null): ?LinkInterface
    {
        $oldprev = $this->linkTrait_prevLink;
        if ($newprev) {
            $this->linkTrait_prevLink = $newprev;
        }
        return $oldprev;
    }
    
    
    
    /**
     * Returns all the chain links as one array, starting with the current link.
     *
     * @param Direction|null $dir
     * @param array          $a
     *
     * @return array
     * @throws \ReflectionException
     */
    function getChainLinksAsArray(Direction $dir = null, array $a = []): array
    {
        if($dir === null) $dir=Direction::__default;
        $dir=new Direction($dir);
        
        $a[]=$this;
        if (($dir == Direction::FORWARD()) && $this->nextLink()) {
//            $a[] = $this->nextLink();
            $a = $this->nextLink()->getChainLinksAsArray($dir, $a);
        }
        if (($dir == Direction::BACKWARD()) && $this->prevLink()) {
//            $a[] = $this->prevLink();
            $a = $this->prevLink()->getChainLinksAsArray($dir, $a);
        }
        
        return $a;
    }
    
    
    
    /**
     * Return the position relative to the anchor.
     *
     * @return int
     */
    function linkPos(): int
    {
        // Is this the anchor?
        if (is_a($this, AnchorInterface::class)) {
            return 0;
        }
        
        // Is there an anchor in right direction
        $idx = 0;
        $l = $this;
        while ($l = $l->nextLink()) {
            $idx--;
            if (is_a($l, AnchorInterface::class)) {
                return $idx;
            }
        }
        
        // Is there an anchor in left direction
        $idx = 0;
        $l = $this;
        while ($l = $l->prevLink()) {
            $idx++;
            if (is_a($l, AnchorInterface::class)) {
                return $idx;
            }
        }
        
        return $idx;
    }
    
    
}
