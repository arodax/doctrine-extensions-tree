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
 * NestedSet Trait.
 *
 * @author Renaat De Muynck <renaat.demuynck@gmail.com>
 */
trait NestedSetEntityTrait
{
    /**
     * @var int
     *
     * @Tree\TreeRoot
     *
     * @ORM\Column(name="root", type="integer", nullable=true)
     */
    #[ORM\Column(name: 'root', type: 'integer', nullable: true)]
    private $root;

    /**
     * @var int
     *
     * @Tree\TreeLevel
     *
     * @ORM\Column(name="lvl", type="integer")
     */
    #[ORM\Column(name: 'lvl', type: 'integer')]
    private $level;

    /**
     * @var int
     *
     * @Tree\TreeLeft
     *
     * @ORM\Column(name="lft", type="integer")
     */
    #[ORM\Column(name: 'lft', type: 'integer')]
    private $left;

    /**
     * @var int
     *
     * @Tree\TreeRight
     *
     * @ORM\Column(name="rgt", type="integer")
     */
    #[ORM\Column(name: 'rgt', type: 'integer')]
    private $right;
}
