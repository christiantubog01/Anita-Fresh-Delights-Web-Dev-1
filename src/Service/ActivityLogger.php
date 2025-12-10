<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\ActivityLog;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

class ActivityLogger
{
    private EntityManagerInterface $em;
    private Security $security;

    public function __construct(EntityManagerInterface $em, Security $security)
    {
        $this->em = $em;
        $this->security = $security;
    }

    /**
     * Logs an activity for the currently logged-in user.
     *
     * @param string $action     The action performed (CREATE, UPDATE, DELETE, LOGIN, LOGOUT, etc.)
     * @param string $targetData Description of the target record (e.g., "Product: Laptop (ID: 14)")
     */
    public function log(string $action, string $targetData): void
    {
        $user = $this->security->getUser();

        if (!$user instanceof User) {
            // No logged-in user, or not your User entity
            return;
        }

        $roles = $user->getRoles();
        $primaryRole = $roles[0] ?? 'ROLE_USER';

        $log = new ActivityLog();
        $log->setUserId($user->getId());
        $log->setUsername($user->getUsername());
        $log->setRole($primaryRole);
        $log->setAction($action);
        $log->setTargetData($targetData);
        $log->setCreatedAt(new \DateTime());

        $this->em->persist($log);
        $this->em->flush();
    }
}
