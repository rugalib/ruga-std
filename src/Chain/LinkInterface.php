<?php

declare(strict_types=1);

namespace Ruga\Std\Chain;


/**
 * Chain Link Interface.
 *
 * @see      LinkTrait
 * @author   Roland Rusch, easy-smart solution GmbH <roland.rusch@easy-smart.ch>
 */
interface LinkInterface
{
    /**
     * Add a chain link to the end of the chain.
     *
     * @param LinkInterface $link Link element to add
     *
     * @return LinkInterface Current element
     */
    function then(LinkInterface $link): LinkInterface;
    
    
    
    /**
     * Add a chain link to the beginning of the chain.
     *
     * @param LinkInterface $link Link element to add
     *
     * @return LinkInterface Current element
     */
    function first(LinkInterface $link): LinkInterface;
    
    
    
    /**
     * Add a chain link before the current object.
     *
     * @param LinkInterface $newlink Link element to add
     *
     * @return LinkInterface Current element
     */
    function insertBefore(LinkInterface $newlink): LinkInterface;
    
    
    
    /**
     * Add a chain link after the current object.
     *
     * @param LinkInterface $newlink Link element to add
     *
     * @return LinkInterface Current element
     */
    function insertAfter(LinkInterface $newlink): LinkInterface;
    
    
    
    /**
     * Returns the left-most link element.
     *
     * @return LinkInterface
     */
    function leftEndLink(): LinkInterface;
    
    
    
    /**
     * Returns the right-most link element.
     *
     * @return LinkInterface
     */
    function rightEndLink(): LinkInterface;
    
    /**
     * Returns the right-most link element.
     *
     * @return LinkInterface
     */
// 	function anchorLink() : LinkInterface;
    
    /**
     * Return the next link object and set a new next link object if $newnext is provided.
     *
     * @param LinkInterface|null $newnext
     *
     * @return LinkInterface
     */
    function nextLink(LinkInterface $newnext = null): ?LinkInterface;
    
    
    
    /**
     * Return the previous link object and set a new previous link object if $newprev is provided.
     *
     * @param LinkInterface|null $newprev
     *
     * @return LinkInterface
     */
    function prevLink(LinkInterface $newprev = null): ?LinkInterface;
    
    
    
    /**
     * Returns all the chain links as one array;
     *
     * @param int   $dir
     * @param array $rightArray
     *
     * @return array
     */
    function getChainLinksAsArray(Direction $dir = null, array $a = []): array;
    
    
    
    /**
     * Return the position relative to the anchor.
     *
     * @return int
     */
    function linkPos(): int;
    
}
