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

use Arodax\Doctrine\Extensions\Tree\Mapping\Annotation as Tree;

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
     * Annotation or attribute to define the tree type
     */
    public const TREE = Tree\Tree::class;

    /*
     * Annotation or attribute to mark field as one which will store left value
     */
    public const LEFT = Tree\Left::class;

    /**
     * @deprecated since 3.2.0 and will be removed in 4.0.0 use Left::class instead
     */
    public const DEPRECATED_LEFT = Tree\TreeLeft::class;

    /*
     * Annotation or attribute to mark field as one which will store right value
     */
    public const RIGHT = Tree\Right::class;

    /**
     * @deprecated since 3.2.0 and will be removed in 4.0.0 use Right::class instead
     */
    public const DEPRECATED_RIGHT = Tree\TreeRight::class;

    /*
     * Annotation or attribute to mark relative parent field
     */
    public const PARENT_NODE = Tree\ParentNode::class;

    /*
     * Annotation or attribute to mark node level
     */
    public const LEVEL = Tree\Level::class;

    /**
     * @deprecated since 3.2.0 and will be removed in 4.0.0 use Level::class instead
     */
    public const DEPRECATED_LEVEL = Tree\TreeLevel::class;

    /*
     * Annotation or attribute to mark field as tree root
     */
    public const ROOT = Tree\Root::class;

    /**
     * @deprecated since 3.2.0 and will be removed in 4.0.0 use Root::class instead
     */
    public const DEPRECATED_ROOT = Tree\TreeRoot::class;

    /*
     * Annotation or attribute to specify closure tree class
     */
    public const CLOSURE = Tree\Closure::class;

    /**
     * @deprecated since 3.2.0 and will be removed in 4.0.0 use Closure::class instead
     */
    public const DEPRECATED_CLOSURE = Tree\TreeClosure::class;

    /*
     * Annotation or attribute to specify path class
     */
    public const PATH = Tree\Path::class;

    /**
     * @deprecated since 3.2.0 and will be removed in 4.0.0 use Path::class instead
     */
    public const DEPRECATED_PATH = Tree\TreePath::class;

    /*
     * Annotation or attribute to specify path source class
     */
    public const PATH_SOURCE = Tree\PathSource::class;

    /*
     * Annotation or attribute to specify path hash class
     */
    public const PATH_HASH = Tree\PathHash::class;

    /*
     * Annotation or attribute to mark the field to be used to hold the lock time
     */
    public const LOCK_TIME = Tree\LockTime::class;

    /**
     * @deprecated since 3.2.0 and will be removed in 4.0.0 use LockTime::class instead
     */
    public const DEPRECATED_LOCK_TIME = Tree\TreeLockTime::class;

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
    public function setAnnotationReader(object $reader);
}
