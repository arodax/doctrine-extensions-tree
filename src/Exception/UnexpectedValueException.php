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

namespace Arodax\Doctrine\Extensions\Tree\Exception;

/**
 * Exception thrown if a value does not match with a set of values.
 *
 * @author Daniel Chodusov <daniel@chodusov.com>
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
class UnexpectedValueException extends \UnexpectedValueException implements TreeExceptionInterface
{
    //
}
