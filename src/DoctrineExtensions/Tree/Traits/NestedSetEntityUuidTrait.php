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

namespace DoctrineExtensions\Tree\Traits;

use Doctrine\ORM\Mapping as ORM;
use DoctrineExtensions\Tree\Mapping\Annotation as Tree;

/**
 * NestedSet Trait with UUid, usable with PHP >= 5.4
 *
 * @author Benjamin Lazarecki <benjamin.lazarecki@sensiolabs.com>
 *
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
trait NestedSetEntityUuid
{
    use NestedSetEntityTrait;

    /**
     * @var string
     * @Tree\TreeRoot
     * @ORM\Column(name="root", type="string", nullable=true)
     */
    private $root;
}
