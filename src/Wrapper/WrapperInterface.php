<?php

/*
 * This file is part of the arodax/doctrine-extensions-common package.
 *
 * (c) ARODAX  <info@arodax.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Arodax\Doctrine\Extensions\Tree\Wrapper;

/**
 * Object wrapper interface
 *
 * @author Gediminas Morkevicius <gediminas.morkevicius@gmail.com>
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
interface WrapperInterface
{
    /**
     * Get currently wrapped object
     * etc.: entity, document
     *
     * @return object
     */
    public function getObject();

    /**
     * Extract property value from object
     *
     * @param string $property
     *
     * @return mixed
     */
    public function getPropertyValue($property);

    /**
     * Set the property
     *
     * @param string $property
     * @param mixed  $value
     *
     * @return WrapperInterface
     */
    public function setPropertyValue($property, $value);

    /**
     * Populates the object with given property values
     *
     * @param array $data
     *
     * @return static
     */
    public function populate(array $data);

    /**
     * Checks if identifier is valid
     *
     * @return boolean
     */
    public function hasValidIdentifier();

    /**
     * Get metadata
     *
     * @return object
     */
    public function getMetadata();

    /**
     * Get the object identifier, single or composite
     *
     * @param boolean $single
     *
     * @return array|mixed
     */
    public function getIdentifier($single = true);

    /**
     * Get root object class name
     *
     * @return string
     */
    public function getRootObjectName();

    /**
     * Chechks if association is embedded
     *
     * @param string $field
     *
     * @return bool
     */
    public function isEmbeddedAssociation($field);
}
