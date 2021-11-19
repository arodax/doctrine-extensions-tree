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

namespace Arodax\Doctrine\Extensions\Tree\Strategy\ORM;

use Arodax\Doctrine\Extensions\Tree\Strategy\AbstractMaterializedPath;
use Arodax\Doctrine\Extensions\Tree\Wrapper\AbstractWrapper;

/**
 * This strategy makes tree using materialized path strategy.
 *
 * @author Gustavo Falco <comfortablynumb84@gmail.com>
 * @author Gediminas Morkevicius <gediminas.morkevicius@gmail.com>
 */
class MaterializedPath extends AbstractMaterializedPath
{
    /**
     * {@inheritdoc}
     */
    public function removeNode($om, $meta, $config, $node)
    {
        $uow = $om->getUnitOfWork();
        $wrapped = AbstractWrapper::wrap($node, $om);

        $path = addcslashes($wrapped->getPropertyValue($config['path']), '%');

        // Remove node's children
        $qb = $om->createQueryBuilder();
        $qb->select('e')
            ->from($config['useObjectClass'], 'e')
            ->where($qb->expr()->like('e.'.$config['path'], $qb->expr()->literal($path.'%')));

        if (isset($config['level'])) {
            $lvlField = $config['level'];
            $lvl = $wrapped->getPropertyValue($lvlField);
            if (!empty($lvl)) {
                $qb->andWhere($qb->expr()->gt('e.'.$lvlField, $qb->expr()->literal($lvl)));
            }
        }

        $results = $qb->getQuery()
            ->execute();

        foreach ($results as $node) {
            $uow->scheduleForDelete($node);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getChildren($om, $meta, $config, $path)
    {
        $path = addcslashes($path, '%');
        $qb = $om->createQueryBuilder($config['useObjectClass']);
        $qb->select('e')
            ->from($config['useObjectClass'], 'e')
            ->where($qb->expr()->like('e.'.$config['path'], $qb->expr()->literal($path.'%')))
            ->andWhere('e.'.$config['path'].' != :path')
            ->orderBy('e.'.$config['path'], 'asc');      // This may save some calls to updateNode
        $qb->setParameter('path', $path);

        return $qb->getQuery()
            ->execute();
    }
}
