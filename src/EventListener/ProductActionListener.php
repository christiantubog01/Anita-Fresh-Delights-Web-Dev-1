<?php

namespace App\EventListener;

use App\Entity\Product;
use App\Service\ActivityLogger;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use Doctrine\ORM\Event\PostPersistEventArgs;
use Doctrine\ORM\Event\PostUpdateEventArgs;
use Doctrine\ORM\Event\PostRemoveEventArgs;

#[AsEntityListener(event: Events::postPersist, method: 'postPersist', entity: Product::class)]
#[AsEntityListener(event: Events::postUpdate, method: 'postUpdate', entity: Product::class)]
#[AsEntityListener(event: Events::postRemove, method: 'postRemove', entity: Product::class)]
class ProductActionListener
{
    public function __construct(private ActivityLogger $activityLogger) {}

    public function postPersist(Product $product, PostPersistEventArgs $args): void
    {
        $stock = $product->getStock();
        $stockId = $stock ? $stock->getId() : 'NULL';
        $this->activityLogger->log(
            "CREATE",
            "Product created: " . $product->getProductName() . " (ID: " . $product->getId() . " Product Description:" . $product->getProductDescription() . " Image:" . $product->getImage() . " Price:" . $product->getPrice() . " Stock ID:" . $stockId .")"
        );
    }

    public function postUpdate(Product $product, PostUpdateEventArgs $args): void
    {
        $stock = $product->getStock();
        $stockId = $stock ? $stock->getId() : 'NULL';
        $this->activityLogger->log(
            "UPDATE",
            "Product updated: " . $product->getProductName() . " (ID: " . $product->getId() . " Product Description:" . $product->getProductDescription() . " Image:" . $product->getImage() . " Price:" . $product->getPrice() . " Stock ID:" . $stockId .")"
        );
    }

    public function postRemove(Product $product, PostRemoveEventArgs $args): void
    {
        // $this->activityLogger->log(
        //     "DELETE",
        //     "Product deleted: " . $product->getProductName() . " (ID: " . $product->getId() . ")"
        // );

         // PLACEHOLDER BECAUSE CONTROLLER ALREADY HAS LOGGER IN DELETE
    }
}
