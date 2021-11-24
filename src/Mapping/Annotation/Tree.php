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
 * Tree annotation for Tree behavioral extension
 *
 * @Annotation
 * @NamedArgumentConstructor
 * @Target("CLASS")
 *
 * @author Gediminas Morkevicius <gediminas.morkevicius@gmail.com>
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
#[\Attribute(\Attribute::TARGET_CLASS)]
final class Tree
{
    public string $type = 'nested';
    public bool $activateLocking = false;
    public int $lockingTimeout = 3;
    public ?string $identifierMethod = null;

    public function __construct(
        string $type = 'nested',
        bool $activateLocking = false,
        int $lockingTimeout = 3,
        ?string $identifierMethod = null,
    ) {

        $this->type = $type;
        $this->activateLocking = $activateLocking;
        $this->lockingTimeout = $lockingTimeout;
        $this->identifierMethod = $identifierMethod;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function isActivateLocking(): bool
    {
        return $this->activateLocking;
    }

    public function getLockingTimeout(): int
    {
        return $this->lockingTimeout;
    }

    public function getIdentifierMethod(): ?string
    {
        return $this->identifierMethod;
    }
}
