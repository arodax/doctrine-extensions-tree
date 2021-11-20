<?php

/*
 * This file is part of the arodax/doctrine-extensions-tree package.
 *
 * (c) ARODAX  <info@arodax.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Arodax\Doctrine\Extensions\Tree\Mapping\Annotation;

use Doctrine\Common\Annotations\Annotation;

/**
 * Tree annotation for Tree behavioral extension
 *
 * @Annotation
 * @Target("CLASS")
 *
 * @author Gediminas Morkevicius <gediminas.morkevicius@gmail.com>
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
#[\Attribute(\Attribute::TARGET_CLASS)]
final class Tree extends Annotation
{
    /**
     * @var string
     */
    public $type = 'nested';

    /**
     * @var string
     */
    public $activateLocking = false;

    /**
     * @var integer
     */
    public $lockingTimeout = 3;

    /**
     * @var string $identifierMethod
     */
    public $identifierMethod;
}
