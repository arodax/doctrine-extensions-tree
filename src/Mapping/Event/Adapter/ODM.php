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

namespace Arodax\Doctrine\Extensions\Tree\Mapping\Event\Adapter;

use Arodax\Doctrine\Extensions\Common\Mapping\Event\Adapter\ODM as BaseAdapterODM;
use Arodax\Doctrine\Extensions\Tree\Mapping\Event\TreeAdapterInterface;

/**
 * Doctrine event adapter for ODM adapted
 * for Tree behavior
 *
 * @author Gediminas Morkevicius <gediminas.morkevicius@gmail.com>
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
final class ODM extends BaseAdapterODM implements TreeAdapterInterface
{
    // Nothing specific yet
}
