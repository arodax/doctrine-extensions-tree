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
    public const TREE = 'Arodax\Doctrine\Extensions\\Tree\\Mapping\\Annotation\\Tree';

    /*
     * Annotation to mark field as one which will store left value
     */
    public const LEFT = 'Arodax\Doctrine\Extensions\\Tree\\Mapping\\Annotation\\TreeLeft';

    /*
     * Annotation to mark field as one which will store right value
     */
    public const RIGHT = 'Arodax\Doctrine\Extensions\\Tree\\Mapping\\Annotation\\TreeRight';

    /*
     * Annotation to mark relative parent field
     */
    public const PARENT = 'Arodax\Doctrine\Extensions\\Tree\\Mapping\\Annotation\\ParentNode';

    /*
     * Annotation to mark node level
     */
    public const LEVEL = 'Arodax\Doctrine\Extensions\\Tree\\Mapping\\Annotation\\TreeLevel';

    /*
     * Annotation to mark field as tree root
     */
    public const ROOT = 'Arodax\Doctrine\Extensions\\Tree\\Mapping\\Annotation\\TreeRoot';

    /*
     * Annotation to specify closure tree class
     */
    public const CLOSURE = 'Arodax\Doctrine\Extensions\\Tree\\Mapping\\Annotation\\TreeClosure';

    /*
     * Annotation to specify path class
     */
    public const PATH = 'Arodax\Doctrine\Extensions\\Tree\\Mapping\\Annotation\\TreePath';

    /*
     * Annotation to specify path source class
     */
    public const PATH_SOURCE = 'Arodax\Doctrine\Extensions\\Tree\\Mapping\\Annotation\\PathSource';

    /*
     * Annotation to specify path hash class
     */
    public const PATH_HASH = 'Arodax\Doctrine\Extensions\\Tree\\Mapping\\Annotation\\PathHash';

    /*
     * Annotation to mark the field to be used to hold the lock time
     */
    public const LOCK_TIME = 'Arodax\Doctrine\Extensions\\Tree\\Mapping\\Annotation\\TreeLockTime';

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
