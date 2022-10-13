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

namespace Arodax\Doctrine\Extensions\Tree\Mapping;

use Arodax\Doctrine\Extensions\Tree\Mapping\Driver\AnnotationInterface;
use Arodax\Doctrine\Extensions\Tree\Mapping\Driver\ChainMapping;
use Arodax\Doctrine\Extensions\Tree\Mapping\Driver\DriverInterface;
use Arodax\Doctrine\Extensions\Tree\Exception\RuntimeException;
use Arodax\Doctrine\Extensions\Tree\Mapping\Driver\AbstractFile as FileDriver;
use Doctrine\Persistence\Mapping\Driver\DefaultFileLocator;
use Doctrine\Persistence\Mapping\Driver\SymfonyFileLocator;
use Doctrine\Persistence\Mapping\Driver\MappingDriver;
use Doctrine\Persistence\Mapping\Driver\MappingDriverChain;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\DoctrineBundle\Mapping\MappingDriver as DoctrineBundleMappingDriver;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Contracts\Cache\ItemInterface;

/**
 * The extension metadata factory is responsible for extension driver
 * initialization and fully reading the extension metadata
 *
 * @author Gediminas Morkevicius <gediminas.morkevicius@gmail.com>
 * @author Daniel Chodusov <daniel@chodusov.com>
 *
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
class ExtensionMetadataFactory
{
    /**
     * Extension driver
     * @var DriverInterface
     */
    protected $driver;

    /**
     * Object manager, entity or document
     * @var object
     */
    protected $objectManager;

    /**
     * Extension namespace
     *
     * @var string
     */
    protected $extensionNamespace;

    /**
     * Custom annotation reader
     *
     * @var object
     */
    protected $annotationReader;

    /**
     * Initializes extension driver
     *
     * @param ObjectManager $objectManager
     * @param string        $extensionNamespace
     * @param object        $annotationReader
     */
    public function __construct(ObjectManager $objectManager, $extensionNamespace, $annotationReader)
    {
        $this->objectManager = $objectManager;
        $this->annotationReader = $annotationReader;
        $this->extensionNamespace = $extensionNamespace;
        $omDriver = $objectManager->getConfiguration()->getMetadataDriverImpl();
        $this->driver = $this->getDriver($omDriver);
    }

    /**
     * Reads extension metadata
     *
     * @param  object $meta
     * @return array  - the metatada configuration
     */
    public function getExtensionMetadata(object $meta): array
    {
        if ($meta->isMappedSuperclass) {
            return [];
        }

        // Optionally if symfony/cache package is installed, cache metadata
        if (class_exists(FilesystemAdapter::class)) {
            $cacheId = self::getCacheId($meta->name, $this->extensionNamespace);;
            return (new FilesystemAdapter())->get($cacheId, function () use ($meta) {
                return $this->getConfigMetaData($meta);
            });
        }

        // if no symfony/cache package is available return fresh
        return $this->getConfigMetaData($meta);
    }

    /**
     * Get the cache id
     *
     * @param  string $className
     * @param  string $extensionNamespace
     * @return string
     */
    public static function getCacheId($className, $extensionNamespace)
    {
        return str_replace($className.'\\$'.strtoupper(str_replace('\\', '_', $extensionNamespace)).'_CLASSMETADATA', '\\', '_');
    }

    /**
     * Get the extended driver instance which will
     * read the metadata required by extension
     *
     * @param  object                            $omDriver
     * @throws RuntimeException if driver was not found in extension
     * @return DriverInterface
     */
    protected function getDriver($omDriver)
    {
        if ($omDriver instanceof DoctrineBundleMappingDriver) {
            $omDriver = $omDriver->getDriver();
        }

        $driver = null;
        $className = get_class($omDriver);
        $driverName = substr($className, strrpos($className, '\\') + 1);

        if ($omDriver instanceof MappingDriverChain || $driverName == 'DriverChain') {
            $driver = new ChainMapping();

            foreach ($omDriver->getDrivers() as $namespace => $nestedOmDriver) {
                $driver->addDriver($this->getDriver($nestedOmDriver), $namespace);
            }

            if ($omDriver->getDefaultDriver() !== null) {
                $driver->setDefaultDriver($this->getDriver($omDriver->getDefaultDriver()));
            }
        } else {
            $driverName = substr($driverName, 0, strpos($driverName, 'Driver'));
            // create driver instance

            $driverClassName = $this->extensionNamespace.'\Mapping\Driver\\'.$driverName;

            if (!class_exists($driverClassName)) {
                $driverClassName = $this->extensionNamespace.'\Mapping\Driver\Annotation';
                if (!class_exists($driverClassName)) {
                    throw new RuntimeException("Failed to fallback to annotation driver: ({$driverClassName}), extension driver was not found.");
                }
            }
            $driver = new $driverClassName();
            $driver->setOriginalDriver($omDriver);
            if ($driver instanceof FileDriver) {
                /** @var $driver FileDriver */
                if ($omDriver instanceof MappingDriver) {
                    $driver->setLocator($omDriver->getLocator());
                } else {
                    $driver->setLocator(new DefaultFileLocator($omDriver->getPaths(), $omDriver->getFileExtension()));
                }
            }

            if ($driver instanceof AnnotationInterface) {
                $driver->setAnnotationReader($this->annotationReader);
            }
        }

        return $driver;
    }

    /**
     * Read config meta data.
     *
     * @param $meta
     * @return array
     */
    private function getConfigMetaData($meta): array
    {
        $config = [];
        $cmf = $this->objectManager->getMetadataFactory();
        $useObjectName = $meta->name;
        // collect metadata from inherited classes
        if (null !== $meta->reflClass) {
            foreach (array_reverse(class_parents($meta->name)) as $parentClass) {
                // read only inherited mapped classes
                if ($cmf->hasMetadataFor($parentClass)) {
                    $class = $this->objectManager->getClassMetadata($parentClass);
                    $this->driver->readExtendedMetadata($class, $config);
                    $isBaseInheritanceLevel = !$class->isInheritanceTypeNone()
                        && !$class->parentClasses
                        && $config
                    ;
                    if ($isBaseInheritanceLevel) {
                        $useObjectName = $class->name;
                    }
                }
            }
            $this->driver->readExtendedMetadata($meta, $config);
        }
        if ($config) {
            $config['useObjectClass'] = $useObjectName;
        }

        return $config;
    }
}
