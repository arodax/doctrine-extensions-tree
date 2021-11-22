<?php

/*
 * This file is part of the arodax/doctrine-extensions-tree package.
 *
 * (c) ARODAX  <info@arodax.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Arodax\Doctrine\Extensions\Tree\Mapping\Driver;

use Arodax\Doctrine\Extensions\Tree\Mapping\Annotation\ParentNode;
use Arodax\Doctrine\Extensions\Tree\Mapping\Annotation\PathHash;
use Arodax\Doctrine\Extensions\Tree\Mapping\Annotation\PathSource;
use Arodax\Doctrine\Extensions\Tree\Mapping\Annotation\Tree;
use Arodax\Doctrine\Extensions\Tree\Mapping\Annotation\TreeClosure;
use Arodax\Doctrine\Extensions\Tree\Mapping\Annotation\TreeLeft;
use Arodax\Doctrine\Extensions\Tree\Mapping\Annotation\TreeLevel;
use Arodax\Doctrine\Extensions\Tree\Mapping\Annotation\TreeLockTime;
use Arodax\Doctrine\Extensions\Tree\Mapping\Annotation\TreePath;
use Arodax\Doctrine\Extensions\Tree\Mapping\Annotation\TreeRight;
use Arodax\Doctrine\Extensions\Tree\Mapping\Annotation\TreeRoot;

/**
 * Annotation driver interface, provides method
 * to set custom annotation reader.
 *
 * @author Gediminas Morkevicius <gediminas.morkevicius@gmail.com>
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
interface AnnotationInterface extends DriverInterface
{
    /*
     * Annotation to define the tree type
     */
    public const TREE = Tree::class;

    /*
     * Annotation to mark field as one which will store left value
     */
    public const LEFT = TreeLeft::class;

    /*
     * Annotation to mark field as one which will store right value
     */
    public const RIGHT = TreeRight::class;

    /*
     * Annotation to mark relative parent field
     */
    public const PARENT = ParentNode::class;

    /*
     * Annotation to mark node level
     */
    public const LEVEL = TreeLevel::class;

    /*
     * Annotation to mark field as tree root
     */
    public const ROOT = TreeRoot::class;

    /*
     * Annotation to specify closure tree class
     */
    public const CLOSURE = TreeClosure::class;

    /*
     * Annotation to specify path class
     */
    public const PATH = TreePath::class;

    /*
     * Annotation to specify path source class
     */
    public const PATH_SOURCE = PathSource::class;

    /*
     * Annotation to specify path hash class
     */
    public const PATH_HASH = PathHash::class;

    /*
     * Annotation to mark the field to be used to hold the lock time
     */
    public const LOCK_TIME = TreeLockTime::class;

    /**
     * Set annotation reader class
     * since older doctrine versions do not provide an interface
     * it must provide these methods:
     *     getClassAnnotations([reflectionClass])
     *     getClassAnnotation([reflectionClass], [name])
     *     getPropertyAnnotations([reflectionProperty])
     *     getPropertyAnnotation([reflectionProperty], [name])
     *
     * @param object $reader - annotation reader class
     */
    public function setAnnotationReader($reader);
}
