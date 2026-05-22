<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;

use App\Entity\Order;

use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\SecurityBundle\Security;

class OrderProcessor implements ProcessorInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private Security $security,
    ) {}

    public function process(
        mixed $data,
        Operation $operation,
        array $uriVariables = [],
        array $context = []
    ): mixed {

        if ($data instanceof Order) {

            // AUTO ASSIGN LOGGED-IN USER
            $data->setUser(
                $this->security->getUser()
            );

            $this->entityManager->persist($data);
            $this->entityManager->flush();
        }

        return $data;
    }
}