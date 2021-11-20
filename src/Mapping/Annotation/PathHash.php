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
 * TreePath annotation for Tree behavioral extension
 *
 * @Annotation
 * @Target("PROPERTY")
 *
 * @author <rocco@roccosportal.com>
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
#[\Attribute(\Attribute::TARGET_PROPERTY)]
final class PathHash extends Annotation
{
    //
}
