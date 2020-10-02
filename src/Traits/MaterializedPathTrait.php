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

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * MaterializedPath Trait.
 *
 * @author Steffen Roßkamp <steffen.rosskamp@gimmickmedia.de>
 */
trait MaterializedPathTrait
{
    /**
     * @var string
     */
    protected $path;

    /**
     * @var self
     */
    protected $parent;

    /**
     * @var int
     */
    protected $level;

    /**
     * @var Collection|self[]
     */
    protected $children;

    /**
     * @var string
     */
    protected $hash;

    /**
     * @param self|null $parent
     *
     * @return MaterializedPathTrait
     */
    public function setParent(self $parent = null): self
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return self
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param string $path
     *
     * @return self
     */
    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @return int
     */
    public function getLevel(): int
    {
        return $this->level;
    }

    /**
     * @param string $hash
     *
     * @return self
     */
    public function setHash(string $hash)
    {
        $this->hash = $hash;

        return $this;
    }

    /**
     * @return string
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * @param Collection|self[] $children
     *
     * @return self
     */
    public function setChildren($children): self
    {
        $this->children = $children;

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getChildren()
    {
        return $this->children = $this->children ?: new ArrayCollection();
    }
}
