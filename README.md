# DoctrineExtensions/Tree

![Build Status](https://api.travis-ci.org/DoctrineExtensions/Tree.svg?branch=master "Build Status")


Tree extensions for Doctrine

Note: this code is a part of the hard fork of 
[Atlantic18/DoctrineExtensions](https://github.com/Atlantic18/DoctrineExtensions).

LICENCE: MIT

## Changelog
- 1.0.3 Implementation of [#2020](https://github.com/Atlantic18/DoctrineExtensions/pull/2020) removed instances of hard coded parent column in queries
- 1.0.2 Added missing repositories from the original extension
- 1.0.1 Implementation of [#2001](https://github.com/Atlantic18/DoctrineExtensions/pull/2001) fixing problem causing wrong left/right order.    

## Symfony integration
There is no flex recipe yet, so you need to manually enable extension.

### Enable entity mappings
**config/packages/doctrine.yaml**
```yam
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

### Enable event subscriber
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

### Usage

#### Nested tree strategy

Annotate your entity:

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

Extend your entity repository:

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

#### Saving tree

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

#### Using repository functions

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

##### Inserting node in different positions      
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

For more examples and usage check original package documentation:
https://github.com/Atlantic18/DoctrineExtensions/blob/v2.4.x/doc/tree.md     
