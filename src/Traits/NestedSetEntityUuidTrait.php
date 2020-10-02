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

namespace Arodax\Doctrine\Extensions\Tree\Traits;

use Doctrine\ORM\Mapping as ORM;
use Arodax\Doctrine\Extensions\Tree\Mapping\Annotation as Tree;

/**
 * NestedSet Trait with UUid.
 *
 * @author Benjamin Lazarecki <benjamin.lazarecki@sensiolabs.com>
 */
trait NestedSetEntityUuidTrait
{
    use NestedSetEntityTrait;

    /**
     * @var string
     *
     * @Tree\TreeRoot
     *
     * @ORM\Column(name="root", type="string", nullable=true)
     */
    private $root;
}
