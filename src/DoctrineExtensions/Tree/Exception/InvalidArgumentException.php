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

namespace DoctrineExtensions\Tree\Exception;

/**
 * Exception thrown if an argument is not of the expected type.
 *
 * @author Daniel Chodusov <daniel@chodusov.com>
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
class InvalidArgumentException extends \InvalidArgumentException implements TreeExceptionInterface
{
    //
}
