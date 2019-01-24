<?php

/*
 * This file is part of the DoctrineExtensions package.
 *
 * (c) ARODAX  <info@arodax.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace DoctrineExtensions\Tree\Mapping\Event\Adapter;

use DoctrineExtensions\Common\Mapping\Event\Adapter\ORM as BaseAdapterORM;
use DoctrineExtensions\Tree\Mapping\Event\TreeAdapterInterface;

/**
 * Doctrine event adapter for ORM adapted
 * for Tree behavior
 *
 * @author Gediminas Morkevicius <gediminas.morkevicius@gmail.com>
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
final class ORM extends BaseAdapterORM implements TreeAdapterInterface
{
    // Nothing specific yet
}
