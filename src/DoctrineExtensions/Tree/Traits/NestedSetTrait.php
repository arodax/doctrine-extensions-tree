<?php

/*
 * This file is part of the DoctrineExtensions package.
 *
 * (c) ARODAX  <info@arodax.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace DoctrineExtensions\Tree\Traits;

/**
 * NestedSet Trait.
 *
 * @author Renaat De Muynck <renaat.demuynck@gmail.com>
 */
trait NestedSetTrait
{
    /**
     * @var int
     */
    private $root;

    /**
     * @var int
     */
    private $level;

    /**
     * @var int
     */
    private $left;

    /**
     * @var int
     */
    private $right;
}
