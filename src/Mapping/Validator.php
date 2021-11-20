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

namespace Arodax\Doctrine\Extensions\Tree\Mapping;

use Arodax\Doctrine\Extensions\Tree\Exception\InvalidMappingException;
use Doctrine\DBAL\Types\Types;

/**
 * This is a validator for all mapping drivers for Tree
 * behavioral extension, containing methods to validate
 * mapping information
 *
 * @author Gustavo Falco <comfortablynumb84@gmail.com>
 * @author Gediminas Morkevicius <gediminas.morkevicius@gmail.com>
 * @author <rocco@roccosportal.com>
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
class Validator
{
    /*
     * List of types which are valid for tree fields
     */
    private array $validTypes = [
        Types::INTEGER,
        Types::SMALLINT,
        Types::BIGINT,
        'int',
    ];

    /*
     * List of types which are valid for the path (materialized path strategy)
     */
    private array $validPathTypes = [
        Types::STRING,
        Types::TEXT,
    ];

    /*
     * List of types which are valid for the path source (materialized path strategy)
     */
    private array $validPathSourceTypes = [
        'id',
        Types::INTEGER,
        Types::SMALLINT,
        Types::BIGINT,
        Types::STRING,
        'int',
        Types::FLOAT,
    ];

    /*
     * List of types which are valid for the path hash (materialized path strategy)
     */
    private array $validPathHashTypes = [
        Types::STRING,
    ];

    /*
     * List of types which are valid for the path source (materialized path strategy)
     */
    private array $validRootTypes = [
        Types::INTEGER,
        Types::SMALLINT,
        Types::BIGINT,
        'int',
        Types::STRING,
        Types::GUID,
    ];

    /*
     * Checks if $field type is valid
     */
    public function isValidField(object $meta, string $field): bool
    {
        $mapping = $meta->getFieldMapping($field);

        return $mapping && in_array($mapping['type'], $this->validTypes);
    }

    /*
     * Checks if $field type is valid for TreePath field
     */
    public function isValidFieldForPath(object $meta, string $field): bool
    {
        $mapping = $meta->getFieldMapping($field);

        return $mapping && in_array($mapping['type'], $this->validPathTypes);
    }

    /*
     * Checks if $field type is valid for PathSource field
     */
    public function isValidFieldForPathSource(object $meta, string $field): bool
    {
        $mapping = $meta->getFieldMapping($field);

        return $mapping && in_array($mapping['type'], $this->validPathSourceTypes);
    }

    /*
     * Checks if $field type is valid for PathHash field
     */
    public function isValidFieldForPathHash(object $meta, string $field): bool
    {
        $mapping = $meta->getFieldMapping($field);

        return $mapping && in_array($mapping['type'], $this->validPathHashTypes);
    }

    /*
     * Checks if $field type is valid for TreeLockTime field
     */
    public function isValidFieldForLockTime(object $meta, string $field): bool
    {
        $mapping = $meta->getFieldMapping($field);

        return $mapping && ($mapping['type'] === 'date' || $mapping['type'] === 'datetime' || $mapping['type'] === 'timestamp');
    }

    /*
     * Checks if $field type is valid for TreeRoot field
     */
    public function isValidFieldForRoot(object $meta, string $field): bool
    {
        $mapping = $meta->getFieldMapping($field);

        return $mapping && in_array($mapping['type'], $this->validRootTypes);
    }

    /**
     * Validates metadata for nested type tree
     *
     * @throws InvalidMappingException
     */
    public function validateNestedTreeMetadata(object $meta, array $config): void
    {
        $missingFields = array();
        if (!isset($config['parent'])) {
            $missingFields[] = 'ancestor';
        }
        if (!isset($config['left'])) {
            $missingFields[] = 'left';
        }
        if (!isset($config['right'])) {
            $missingFields[] = 'right';
        }
        if ($missingFields) {
            throw new InvalidMappingException("Missing properties: ".implode(', ', $missingFields)." in class - {$meta->name}");
        }
    }

    /**
     * Validates metadata for closure type tree
     *
     * @throws InvalidMappingException
     */
    public function validateClosureTreeMetadata(object $meta, array $config): void
    {
        $missingFields = array();
        if (!isset($config['parent'])) {
            $missingFields[] = 'ancestor';
        }
        if (!isset($config['closure'])) {
            $missingFields[] = 'closure class';
        }
        if ($missingFields) {
            throw new InvalidMappingException("Missing properties: ".implode(', ', $missingFields)." in class - {$meta->name}");
        }
    }

    /**
     * Validates metadata for materialized path type tree
     *
     * @throws InvalidMappingException
     */
    public function validateMaterializedPathTreeMetadata(object $meta, array $config): void
    {
        $missingFields = array();
        if (!isset($config['parent'])) {
            $missingFields[] = 'ancestor';
        }
        if (!isset($config['path'])) {
            $missingFields[] = 'path';
        }
        if (!isset($config['path_source'])) {
            $missingFields[] = 'path_source';
        }
        if ($missingFields) {
            throw new InvalidMappingException("Missing properties: ".implode(', ', $missingFields)." in class - {$meta->name}");
        }
    }
}
