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
 * NestedSet Trait, usable with PHP >= 5.4
 *
 * @author Renaat De Muynck <renaat.demuynck@gmail.com>
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
trait NestedSetEntityTrait
{
    /**
     * @var integer
     * @Tree\TreeRoot
     * @ORM\Column(name="root", type="integer", nullable=true)
     */
    private $root;

    /**
     * @var integer
     * @Tree\TreeLevel
     * @ORM\Column(name="lvl", type="integer")
     */
    private $level;

    /**
     * @var integer
     * @Tree\TreeLeft
     * @ORM\Column(name="lft", type="integer")
     */
    private $left;

    /**
     * @var integer
     * @Tree\TreeRight
     * @ORM\Column(name="rgt", type="integer")
     */
    private $right;
}
