# Hierarchical Tree extension for Doctrine

![Licence MIT](https://img.shields.io/packagist/l/doctrine-extensions/tree?style=flat)
![Build Status](https://api.travis-ci.org/DoctrineExtensions/Tree.svg?branch=master&style=flat "Build Status")
![contributions welcome](https://img.shields.io/badge/contributions-welcome-brightgreen.svg?style=flat&color=orange)
![downloads total](https://img.shields.io/packagist/dt/doctrine-extensions/tree?color=blue&style=flat)

This extension allows you to store your data in hierarchicaly in your database using Doctrine ORM.

Note: this code is a part of the hard fork of 
[Atlantic18/DoctrineExtensions](https://github.com/Atlantic18/DoctrineExtensions) 2.4.x branch. The goal of this repository is to split original extensions into small packages, each package responsible for the single task. 

Any fixes to the original tree code will be applied to this package whenever aplicable, see the changelog for for more information.

## Licence

MIT

## Changelog
- 1.0.3 Implementation of [#2020](https://github.com/Atlantic18/DoctrineExtensions/pull/2020) removed instances of hard coded parent column in queries
- 1.0.2 Added missing repositories from the original extension
- 1.0.1 Implementation of [#2001](https://github.com/Atlantic18/DoctrineExtensions/pull/2001) fixing problem causing wrong left/right order.    

## Installation
Install the extension with the [composer](https://getcomposer.org)

`composer require doctrine-extensions/tree`

### Using in the Symfony project
There is no flex recipe yet, so you need to manually enable extension by adding the following into your configuration files

**config/packages/doctrine.yaml**
```yaml
parameters:
    ...   
doctrine:
    dbal:
        ...
    orm:
        ...
        mappings:
            ...                
            DoctrineExtensions\Tree:
                is_bundle: false
                type: annotation
                dir: '%kernel.project_dir%/vendor/doctrine-extensions/tree/src/DoctrineExtensions/Tree/Entity'
                prefix: 'DoctrineExtensions\Tree\Entity'
```

**config/services/doctrine.yaml**

```yaml
parameters:
    ...

services:
    ...
    DoctrineExtensions\Tree\TreeSubscriber:
        class: DoctrineExtensions\Tree\TreeSubscriber
        tags:
            - { name: doctrine.event_subscriber, connection: default }
        calls:
            - [ setAnnotationReader, [ '@annotation_reader' ] ]
```

## Prepare entity for the hierarchical tree

### Annotate your entity:

```php
<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use DoctrineExtensions\Tree\Mapping\Annotation as Tree;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CategoryRepository")
 * @Tree\Tree(type="nested")
 */
class Category
{

    /**
     * @var integer
     *
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Tree\TreeLeft
     * @ORM\Column(name="lft", type="integer")
     */
    private $lft;

    /**
     * @Tree\TreeLevel
     * @ORM\Column(name="lvl", type="integer")
     */
    private $lvl;

    /**
     * @Tree\TreeRight()
     * @ORM\Column(name="rgt", type="integer")
     */
    private $rgt;

    /**
     * @Tree\TreeRoot()
     * @ORM\ManyToOne(targetEntity="MenuItem")
     * @ORM\JoinColumn(name="tree_root", referencedColumnName="id", onDelete="CASCADE")
     */
    private $root;

    /**
     * @Tree\ParentNode()
     * @ORM\ManyToOne(targetEntity="MenuItem", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity="MenuItem", mappedBy="parent")
     * @ORM\OrderBy({"lft" = "ASC"})
     */
    private $children;
```

### Extend the repository

Extend your entity repository from `DoctrineExtensions\Tree\Entity\Repository\NestedTreeRepository` this allows you to use special methods for working with the tree:

```php
<?php

namespace App\Repository\Core\Menu;

use App\Entity\Category;
use DoctrineExtensions\Tree\Entity\Repository\NestedTreeRepository;

class MenuItemRepository extends NestedTreeRepository
{
    //
}

```

## Usage

### Saving tree

Save some categories:

```php
<?php

$food = new Category();
$food->setTitle('Food');

$fruits = new Category();
$fruits->setTitle('Fruits');
$fruits->setParent($food);

$vegetables = new Category();
$vegetables->setTitle('Vegetables');
$vegetables->setParent($food);

$carrots = new Category();
$carrots->setTitle('Carrots');
$carrots->setParent($vegetables);

$this->em->persist($food);
$this->em->persist($fruits);
$this->em->persist($vegetables);
$this->em->persist($carrots);
$this->em->flush();
```

The result after flush will generate the food tree:

```
food (1-8)
    /fruits (2-3)
    /vegetables (4-7)
        /carrots (5-6)
```

### Inserting node in different positions      
```php      
<?php

$food = new Category();
$food->setTitle('Food');

$fruits = new Category();
$fruits->setTitle('Fruits');

$vegetables = new Category();
$vegetables->setTitle('Vegetables');

$carrots = new Category();
$carrots->setTitle('Carrots');

$treeRepository
    ->persistAsFirstChild($food)
    ->persistAsFirstChildOf($fruits, $food)
    ->persistAsLastChildOf($vegetables, $food)
    ->persistAsNextSiblingOf($carrots, $fruits);

$em->flush();
```

### Using repository functions

```php
<?php

$repo = $em->getRepository('Entity\Category');

$food = $repo->findOneByTitle('Food');
echo $repo->childCount($food);
// prints: 3
echo $repo->childCount($food, true/*direct*/);
// prints: 2
$children = $repo->children($food);
// $children contains:
// 3 nodes
$children = $repo->children($food, false, 'title');
// will sort the children by title
$carrots = $repo->findOneByTitle('Carrots');
$path = $repo->getPath($carrots);
/* $path contains:
   0 => Food
   1 => Vegetables
   2 => Carrots
*/

// verification and recovery of tree
$repo->verify();
// can return TRUE if tree is valid, or array of errors found on tree
$repo->recover();
$em->flush(); // important: flush recovered nodes
// if tree has errors it will try to fix all tree nodes

// UNSAFE: be sure to backup before running this method when necessary, if you can use $em->remove($node);
// which would cascade to children
// single node removal
$vegies = $repo->findOneByTitle('Vegetables');
$repo->removeFromTree($vegies);
$em->clear(); // clear cached nodes
// it will remove this node from tree and reparent all children

// reordering the tree
$food = $repo->findOneByTitle('Food');
$repo->reorder($food, 'title');
// it will reorder all "Food" tree node left-right values by the title
```



For more examples and usage check original package documentation:
https://github.com/Atlantic18/DoctrineExtensions/blob/v2.4.x/doc/tree.md     
