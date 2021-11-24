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

use Arodax\Doctrine\Extensions\Tree\Exception\InvalidMappingException;
use Arodax\Doctrine\Extensions\Tree\Mapping\Annotation\Tree;
use Arodax\Doctrine\Extensions\Tree\Mapping\Annotation\TreePath;
use Arodax\Doctrine\Extensions\Tree\Mapping\Annotation\TreeRoot;
use Arodax\Doctrine\Extensions\Tree\Mapping\Validator;
use Doctrine\ORM\Mapping\ClassMetadata;

/**
 * This is an attribute mapping driver, which allows native PHP attributes to be used.
 *
 * @author Daniel Chodusov <daniel@chodusov.com>
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
class Attribute extends AbstractPropertyDriver implements DriverInterface
{
    /**
     * @throws InvalidMappingException
     * @throws \ReflectionException
     */
    public function readExtendedMetadata(ClassMetadata $meta, array &$config)
    {
        $validator = new Validator();
        $class = $this->getMetaReflectionClass($meta);

        // class attributes
        $attributes = $class->getAttributes(self::TREE);
        if (!empty($attributes)) {

            if (count($attributes) > 1) {
                throw new InvalidMappingException(sprintf("There must be only one %s class attribute,
                 but %s attributes specified", self::TREE, count($attributes)));
            }

            /** @var Tree $attribute */
            $attribute = $attributes[0]->newInstance();

            if (!in_array($attribute->type, $this->strategies)) {
                throw new InvalidMappingException("Tree type: {$attribute->type} is not available.");
            }

            $config['strategy'] = $attribute->type;
            $config['activate_locking'] = $attribute->activateLocking;
            $config['locking_timeout'] = (int) $attribute->lockingTimeout;

            if ($config['locking_timeout'] < 1) {
                throw new InvalidMappingException('Tree Locking Timeout must be at least of 1 second.');
            }
        }

        if (!empty($class->getAttributes(self::CLOSURE))) {
            $attributes = $class->getAttributes(self::CLOSURE);
        } else {
            $attributes = $class->getAttributes(self::DEPRECATED_CLOSURE);
        }


        if (!empty($attributes)) {

            if (count($attributes) > 1) {
                throw new InvalidMappingException(sprintf("There must be only one %s attribute,
                 but %s attributes specified", self::CLOSURE, count($attributes)));
            }


            if (!$cl = $this->getRelatedClassName($meta, $class->getName())) {
                throw new InvalidMappingException("Tree closure class: {$class->getName()} does not exist.");
            }

            $config['closure'] = $cl;
        }

        // property attributes
        foreach ($class->getProperties() as $property) {

            if ($meta->isMappedSuperclass && !$property->isPrivate() ||
                $meta->isInheritedField($property->name) ||
                isset($meta->associationMappings[$property->name]['inherited'])
            ) {
                continue;
            }

            // left
            if (!empty($property->getAttributes(self::LEFT) || !empty($property->getAttributes(self::DEPRECATED_LEFT)))) {
                $field = $property->getName();
                if (!$meta->hasField($field)) {
                    throw new InvalidMappingException("Unable to find 'left' - [{$field}] as mapped property in entity - {$meta->name}");
                }
                if (!$validator->isValidField($meta, $field)) {
                    throw new InvalidMappingException("Tree left field - [{$field}] type is not valid and must be 'integer' in class - {$meta->name}");
                }
                $config['left'] = $field;
            }

            // right
            if (!empty($property->getAttributes(self::RIGHT) || !empty($property->getAttributes(self::DEPRECATED_RIGHT)))) {
                $field = $property->getName();
                if (!$meta->hasField($field)) {
                    throw new InvalidMappingException("Unable to find 'right' - [{$field}] as mapped property in entity - {$meta->name}");
                }
                if (!$validator->isValidField($meta, $field)) {
                    throw new InvalidMappingException("Tree right field - [{$field}] type is not valid and must be 'integer' in class - {$meta->name}");
                }
                $config['right'] = $field;
            }

            // ancestor/parent
            if (!empty($property->getAttributes(self::PARENT_NODE))) {
                $field = $property->getName();
                if (!$meta->isSingleValuedAssociation($field)) {
                    throw new InvalidMappingException("Unable to find ancestor/parent child relation through ancestor field - [{$field}] in class - {$meta->name}");
                }
                $config['parent'] = $field;
            }

            // root
            if (!empty($property->getAttributes(self::ROOT) || !empty($property->getAttributes(self::DEPRECATED_ROOT)))) {
                $field = $property->getName();

                if (!empty($property->getAttributes(self::ROOT))) {
                    $attributes = $property->getAttributes(self::ROOT);
                } else {
                    $attributes = $property->getAttributes(self::DEPRECATED_ROOT);
                }

                if (count($attributes) > 1) {
                    throw new InvalidMappingException(sprintf("There must be only one %s attribute,
                 but %s attributes specified at %s", self::ROOT, count($attributes), $field));
                }

                if (!$meta->isSingleValuedAssociation($field)) {
                    if (!$meta->hasField($field)) {
                        throw new InvalidMappingException("Unable to find 'root' - [{$field}] as mapped property in entity - {$meta->name}");
                    }

                    if (!$validator->isValidFieldForRoot($meta, $field)) {
                        throw new InvalidMappingException(
                            "Tree root field should be either a literal property ('integer' types or 'string') or a many-to-one association through root field - [{$field}] in class - {$meta->name}"
                        );
                    }
                }

                /** @var TreeRoot $attribute */
                $attribute = $attributes[0]->newInstance();

                $config['rootIdentifierMethod'] =  $attribute->identifierMethod;
                $config['root'] = $field;
            }

            // level
            if (!empty($property->getAttributes(self::LEVEL) || !empty($property->getAttributes(self::DEPRECATED_LEVEL)))) {
                $field = $property->getName();
                if (!$meta->hasField($field)) {
                    throw new InvalidMappingException("Unable to find 'level' - [{$field}] as mapped property in entity - {$meta->name}");
                }
                if (!$validator->isValidField($meta, $field)) {
                    throw new InvalidMappingException("Tree level field - [{$field}] type is not valid and must be 'integer' in class - {$meta->name}");
                }
                $config['level'] = $field;
            }

            // path
            if (!empty($attributes = $property->getAttributes(self::PATH) || !empty($property->getAttributes(self::DEPRECATED_PATH)))) {
                $field = $property->getName();
                if (!$meta->hasField($field)) {
                    throw new InvalidMappingException("Unable to find 'path' - [{$field}] as mapped property in entity - {$meta->name}");
                }
                if (!$validator->isValidFieldForPath($meta, $field)) {
                    throw new InvalidMappingException("Tree TreePath field - [{$field}] type is not valid. It must be string or text in class - {$meta->name}");
                }

                if (count($attributes) > 1) {
                    throw new InvalidMappingException(sprintf("There must be only one %s attribute,
                 but %s attributes specified at %s", self::PATH, count($attributes), $field));
                }

                if (!empty($property->getAttributes(self::PATH))) {
                    $attributes = $property->getAttributes(self::PATH);
                } else {
                    $attributes = $property->getAttributes(self::DEPRECATED_PATH);
                }

                /** @var TreePath $attribute */
                $attribute = $attributes[0]->newInstance();

                if (strlen($attribute->separator) > 1) {
                    throw new InvalidMappingException("Tree TreePath field - [{$field}] Separator {$attribute->separator} is invalid. It must be only one character long.");
                }
                $config['path'] = $field;
                $config['path_separator'] = $attribute->separator;
                $config['path_append_id'] = $attribute->appendId;
                $config['path_starts_with_separator'] = $attribute->startsWithSeparator;
                $config['path_ends_with_separator'] = $attribute->endsWithSeparator;
            }

            // path source
            if (!empty($property->getAttributes(self::PATH_SOURCE))) {
                $field = $property->getName();
                if (!$meta->hasField($field)) {
                    throw new InvalidMappingException("Unable to find 'path_source' - [{$field}] as mapped property in entity - {$meta->name}");
                }
                if (!$validator->isValidFieldForPathSource($meta, $field)) {
                    throw new InvalidMappingException("Tree PathSource field - [{$field}] type is not valid. It can be any of the integer variants, double, float or string in class - {$meta->name}");
                }
                $config['path_source'] = $field;
            }

            // path hash
            if (!empty($property->getAttributes(self::PATH_SOURCE))) {
                $field = $property->getName();
                if (!$meta->hasField($field)) {
                    throw new InvalidMappingException("Unable to find 'path_hash' - [{$field}] as mapped property in entity - {$meta->name}");
                }
                if (!$validator->isValidFieldForPathHash($meta, $field)) {
                    throw new InvalidMappingException("Tree PathHash field - [{$field}] type is not valid. It can be any of the integer variants, double, float or string in class - {$meta->name}");
                }
                $config['path_hash'] = $field;
            }

            // lock time
            if (!empty($property->getAttributes(self::LOCK_TIME) || !empty($property->getAttributes(self::DEPRECATED_LOCK_TIME)))) {
                $field = $property->getName();
                if (!$meta->hasField($field)) {
                    throw new InvalidMappingException("Unable to find 'lock_time' - [{$field}] as mapped property in entity - {$meta->name}");
                }
                if (!$validator->isValidFieldForLockTime($meta, $field)) {
                    throw new InvalidMappingException("Tree PathSource field - [{$field}] type is not valid. It must be \"date\" in class - {$meta->name}");
                }
                $config['lock_time'] = $field;
            }

            if (isset($config['activate_locking']) && $config['activate_locking'] && !isset($config['lock_time'])) {
                throw new InvalidMappingException('You need to map a date field as the tree lock time field to activate locking support.');
            }
        }

        if (!$meta->isMappedSuperclass && $config) {
            if (isset($config['strategy'])) {
                if (is_array($meta->identifier) && count($meta->identifier) > 1) {
                    throw new InvalidMappingException("Tree does not support composite identifiers in class - {$meta->name}");
                }
                $method = 'validate'.ucfirst($config['strategy']).'TreeMetadata';
                $validator->$method($meta, $config);
            } else {
                throw new InvalidMappingException("Cannot find Tree type for class: {$meta->name}");
            }
        }
    }
}
