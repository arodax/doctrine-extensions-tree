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
 * TreeRoot annotation for Tree behavioral extension
 *
 * @Annotation
 * @NamedArgumentConstructor
 * @Target("PROPERTY")
 *
 * @author Gediminas Morkevicius <gediminas.morkevicius@gmail.com>
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
#[\Attribute(\Attribute::TARGET_PROPERTY)]
class Root
{
    public ?string $identifierMethod = null;

    public function __construct(?string $identifierMethod = null)
    {
        $this->identifierMethod = $identifierMethod;
    }

    public function getIdentifierMethod(): ?string
    {
        return $this->identifierMethod;
    }
}