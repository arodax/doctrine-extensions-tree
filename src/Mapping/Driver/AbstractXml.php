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

namespace Arodax\Doctrine\Extensions\Tree\Mapping\Driver;

use Arodax\Doctrine\Extensions\Common\Exception\InvalidMappingException;
use SimpleXMLElement;

/**
 * The mapping XmlDriver abstract class, defines the
 * metadata extraction function common among all
 * all drivers used on these extensions by file based
 * drivers.
 *
 * @author Miha Vrhovnik <miha.vrhovnik@gmail.com>
 * @author Gediminas Morkevicius <gediminas.morkevicius@gmail.com>
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
abstract class AbstractXml extends AbstractFile
{
    const DOCTRINE_NAMESPACE_URI = 'http://doctrine-project.org/schemas/orm/doctrine-mapping';

    /**
     * File extension
     * @var string
     */
    protected $_extension = '.dcm.xml';

    /**
     * Get attribute value.
     * As we are supporting namespaces the only way to get to the attributes under a node is to use attributes function on it
     *
     * @param SimpleXMLElement $node
     * @param string           $attributeName
     *
     * @return string
     */
    protected function _getAttribute(SimpleXmlElement $node, $attributeName)
    {
        $attributes = $node->attributes();

        return (string) $attributes[$attributeName];
    }

    /**
     * Get boolean attribute value.
     * As we are supporting namespaces the only way to get to the attributes under a node is to use attributes function on it
     *
     * @param SimpleXMLElement $node
     * @param string $attributeName
     *
     * @return boolean
     * @throws InvalidMappingException
     */
    protected function _getBooleanAttribute(SimpleXmlElement $node, $attributeName)
    {
        $rawValue = strtolower($this->_getAttribute($node, $attributeName));
        if ($rawValue === '1' || $rawValue === 'true') {
            return true;
        }
        if ($rawValue === '0' || $rawValue === 'false') {
            return false;
        }
        throw new InvalidMappingException(sprintf("Attribute %s must have a valid boolean value, '%s' found", $attributeName, $this->_getAttribute($node, $attributeName)));
    }

    /**
     * does attribute exist under a specific node
     * As we are supporting namespaces the only way to get to the attributes under a node is to use attributes function on it
     *
     * @param SimpleXMLElement $node
     * @param string           $attributeName
     *
     * @return string
     */
    protected function _isAttributeSet(SimpleXmlElement $node, $attributeName)
    {
        $attributes = $node->attributes();

        return isset($attributes[$attributeName]);
    }

    /**
     * {@inheritDoc}
     */
    protected function _loadMappingFile($file)
    {
        $result = array();
        $xmlElement = simplexml_load_file($file);
        $xmlElement = $xmlElement->children(self::DOCTRINE_NAMESPACE_URI);

        if (isset($xmlElement->entity)) {
            foreach ($xmlElement->entity as $entityElement) {
                $entityName = $this->_getAttribute($entityElement, 'name');
                $result[$entityName] = $entityElement;
            }
        } elseif (isset($xmlElement->{'mapped-superclass'})) {
            foreach ($xmlElement->{'mapped-superclass'} as $mappedSuperClass) {
                $className = $this->_getAttribute($mappedSuperClass, 'name');
                $result[$className] = $mappedSuperClass;
            }
        }

        return $result;
    }
}
