<?php

/*
 * This file is part of the DoctrineExtensions package.
 *
 * (c) ARODAX  <info@arodax.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.*
 */

declare(strict_types = 1);

namespace DoctrineExtensions\Tree;

use DoctrineExtensions\Tree\Exception\InvalidArgumentException;

interface RepositoryUtilsInterface
{
    /**
     * Retrieves the nested array or the decorated output.
     *
     * Uses options to handle decorations
     *
     * @throws InvalidArgumentException
     *
     * @param object  $node        - from which node to start reordering the tree
     * @param boolean $direct      - true to take only direct children
     * @param array   $options     :
     *                             decorate: boolean (false) - retrieves tree as UL->LI tree
     *                             nodeDecorator: TreeClosure (null) - uses $node as argument and returns decorated item as string
     *                             rootOpen: string || TreeClosure ('<ul>') - branch start, closure will be given $children as a parameter
     *                             rootClose: string ('</ul>') - branch close
     *                             childStart: string || TreeClosure ('<li>') - start of node, closure will be given $node as a parameter
     *                             childClose: string ('</li>') - close of node
     *                             childSort: array || keys allowed: field: field to sort on, dir: direction. 'asc' or 'desc'
     * @param boolean $includeNode - Include node on results?
     *
     * @return array|string
     */
    public function childrenHierarchy($node = null, $direct = false, array $options = array(), $includeNode = false);

    /**
     * Retrieves the nested array or the decorated output.
     *
     * Uses options to handle decorations
     * NOTE: nodes should be fetched and hydrated as array
     *
     * @throws InvalidArgumentException
     *
     * @param array $nodes   - list o nodes to build tree
     * @param array $options :
     *                       decorate: boolean (false) - retrieves tree as UL->LI tree
     *                       nodeDecorator: TreeClosure (null) - uses $node as argument and returns decorated item as string
     *                       rootOpen: string || TreeClosure ('<ul>') - branch start, closure will be given $children as a parameter
     *                       rootClose: string ('</ul>') - branch close
     *                       childStart: string || TreeClosure ('<li>') - start of node, closure will be given $node as a parameter
     *                       childClose: string ('</li>') - close of node
     *
     * @return array|string
     */
    public function buildTree(array $nodes, array $options = array());

    /**
     * Process nodes and produce an array with the
     * structure of the tree
     *
     * @param array $nodes - Array of nodes
     *
     * @return array - Array with tree structure
     */
    public function buildTreeArray(array $nodes);

    /**
     * Sets the current children index.
     *
     * @param string $childrenIndex
     */
    public function setChildrenIndex($childrenIndex);

    /**
     * Gets the current children index.
     *
     * @return string
     */
    public function getChildrenIndex();
}
