<?php

namespace App\EventListener;

use App\Entity\User;
use App\Service\ActivityLogger;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use Doctrine\ORM\Event\PostPersistEventArgs;
use Doctrine\ORM\Event\PostUpdateEventArgs;
use Doctrine\ORM\Event\PostRemoveEventArgs;

#[AsEntityListener(event: Events::postPersist, method: 'postPersist', entity: User::class)]
#[AsEntityListener(event: Events::postUpdate, method: 'postUpdate', entity: User::class)]
#[AsEntityListener(event: Events::postRemove, method: 'postRemove', entity: User::class)]
class UserActionListener
{
    public function __construct(private ActivityLogger $activityLogger) {}

    public function postPersist(User $user, PostPersistEventArgs $args): void
    {
        $this->activityLogger->log(
            "CREATE",
            "User created: " . $user->getUsername() . " (ID: " . $user->getId() . ")"
        );
    }

    public function postUpdate(User $user, PostUpdateEventArgs $args): void
    {
        $this->activityLogger->log(
            "UPDATE",
            "User updated: " . $user->getUsername() . " (ID: " . $user->getId() . ")"
        );
    }

    public function postRemove(User $user, PostRemoveEventArgs $args): void
    {
        
        // PLACEHOLDER BECAUSE CONTROLLER ALREADY HAS LOGGER IN DELETE
    }
}
