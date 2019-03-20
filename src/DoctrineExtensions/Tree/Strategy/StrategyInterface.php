<?php

/*
 * This file is part of the DoctrineExtensions package.
 *
 * (c) ARODAX  <info@arodax.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace DoctrineExtensions\Tree\Strategy;

use DoctrineExtensions\Common\Mapping\Event\AdapterInterface;
use DoctrineExtensions\Tree\TreeSubscriber;
use Doctrine\Common\Persistence\ObjectManager;

interface StrategyInterface
{
    /**
     * NestedSet strategy.
     */
    public const NESTED = 'nested';

    /**
     * TreeClosure strategy.
     */
    public const CLOSURE = 'closure';

    /**
     * Materialized TreePath strategy.
     */
    public const MATERIALIZED_PATH = 'materializedPath';

    /**
     * Get the name of strategy.
     *
     * @return string
     */
    public function getName();

    /**
     * Initialize strategy with tree listener.
     *
     * @param TreeSubscriber $listener
     */
    public function __construct(TreeSubscriber $listener);

    /**
     * Operations after metadata is loaded.
     *
     * @param ObjectManager $om
     * @param object        $meta
     */
    public function processMetadataLoad($om, $meta);

    /**
     * Operations on tree node insertion.
     *
     * @param ObjectManager    $om     - object manager
     * @param object           $object - node
     * @param AdapterInterface $ea     - event adapter
     */
    public function processScheduledInsertion($om, $object, AdapterInterface $ea);

    /**
     * Operations on tree node updates.
     *
     * @param ObjectManager    $om     - object manager
     * @param object           $object - node
     * @param AdapterInterface $ea     - event adapter
     */
    public function processScheduledUpdate($om, $object, AdapterInterface $ea);

    /**
     * Operations on tree node delete.
     *
     * @param ObjectManager $om     - object manager
     * @param object        $object - node
     */
    public function processScheduledDelete($om, $object);

    /**
     * Operations on tree node removal.
     *
     * @param ObjectManager $om     - object manager
     * @param object        $object - node
     */
    public function processPreRemove($om, $object);

    /**
     * Operations on tree node persist.
     *
     * @param ObjectManager $om     - object manager
     * @param object        $object - node
     */
    public function processPrePersist($om, $object);

    /**
     * Operations on tree node update.
     *
     * @param ObjectManager $om     - object manager
     * @param object        $object - node
     */
    public function processPreUpdate($om, $object);

    /**
     * Operations on tree node insertions.
     *
     * @param ObjectManager    $om     - object manager
     * @param object           $object - node
     * @param AdapterInterface $ea     - event adapter
     */
    public function processPostPersist($om, $object, AdapterInterface $ea);

    /**
     * Operations on tree node updates.
     *
     * @param ObjectManager    $om     - object manager
     * @param object           $object - node
     * @param AdapterInterface $ea     - event adapter
     */
    public function processPostUpdate($om, $object, AdapterInterface $ea);

    /**
     * Operations on tree node removals.
     *
     * @param ObjectManager    $om     - object manager
     * @param object           $object - node
     * @param AdapterInterface $ea     - event adapter
     */
    public function processPostRemove($om, $object, AdapterInterface $ea);

    /**
     * Operations on the end of flush process.
     *
     * @param ObjectManager    $om - object manager
     * @param AdapterInterface $ea - event adapter
     */
    public function onFlushEnd($om, AdapterInterface $ea);
}
