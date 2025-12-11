<?php

namespace App\EventListener;

use App\Entity\Stock;
use App\Service\ActivityLogger;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use Doctrine\ORM\Event\PostPersistEventArgs;
use Doctrine\ORM\Event\PostUpdateEventArgs;
use Doctrine\ORM\Event\PostRemoveEventArgs;

#[AsEntityListener(event: Events::postPersist, method: 'postPersist', entity: Stock::class)]
#[AsEntityListener(event: Events::postUpdate, method: 'postUpdate', entity: Stock::class)]
#[AsEntityListener(event: Events::postRemove, method: 'postRemove', entity: Stock::class)]
class StockActionListener
{
    public function __construct(private ActivityLogger $activityLogger) {}

    public function postPersist(Stock $stock, PostPersistEventArgs $args): void
    {
        $this->activityLogger->log(
            "CREATE",
            "Stock created: " . $stock->getStockDescription()
        );
    }

    public function postUpdate(Stock $stock, PostUpdateEventArgs $args): void
    {
        $this->activityLogger->log(
            "UPDATE",
            "Stock updated: " . $stock->getStockDescription()
        );
    }

    public function postRemove(Stock $stock, PostRemoveEventArgs $args): void
    {
        $this->activityLogger->log(
            "DELETE",
            "Stock deleted: " . $stock->getStockDescription()
        );
    }
}
