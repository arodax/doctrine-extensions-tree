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

namespace Arodax\Doctrine\Extensions\Tree\Mapping\Annotation;

use Doctrine\Common\Annotations\Annotation;
use Doctrine\Common\Annotations\Annotation\NamedArgumentConstructor;

/**
 * @deprecated since 3.2.0 and will be removed in 4.0.0 use Path::class instead
 *
 * TreePath annotation for Tree behavioral extension
 *
 * @Annotation
 * @NamedArgumentConstructor
 * @Target("PROPERTY")
 *
 * @author Gustavo Falco <comfortablynumb84@gmail.com>
 * @author Gediminas Morkevicius <gediminas.morkevicius@gmail.com>
 * @author <rocco@roccosportal.com>
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
#[\Attribute(\Attribute::TARGET_PROPERTY)]
final class TreePath extends Path
{
}
