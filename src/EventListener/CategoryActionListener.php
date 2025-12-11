<?php

namespace App\EventListener;

use App\Entity\Category;
use App\Service\ActivityLogger;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use Doctrine\ORM\Event\PostPersistEventArgs;
use Doctrine\ORM\Event\PostUpdateEventArgs;
use Doctrine\ORM\Event\PostRemoveEventArgs;

#[AsEntityListener(event: Events::postPersist, method: 'postPersist', entity: Category::class)]
#[AsEntityListener(event: Events::postUpdate, method: 'postUpdate', entity: Category::class)]
#[AsEntityListener(event: Events::postRemove, method: 'postRemove', entity: Category::class)]
class CategoryActionListener
{
    public function __construct(private ActivityLogger $activityLogger) {}

    public function postPersist(Category $category, PostPersistEventArgs $args): void
    {
        $this->activityLogger->log(
            "CREATE",
            "Category created: " . $category->getCategoryName()
        );
    }

    public function postUpdate(Category $category, PostUpdateEventArgs $args): void
    {
        $this->activityLogger->log(
            "UPDATE",
            "Category updated: " . $category->getCategoryName()
        );
    }

    public function postRemove(Category $category, PostRemoveEventArgs $args): void
    {
        $this->activityLogger->log(
            "DELETE",
            "Category deleted: " . $category->getCategoryName()
        );
    }
}
