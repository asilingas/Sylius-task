<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

// Helper methods for recurring actions
trait HelperTrait
{
    protected EntityManagerInterface $em;

    public function update(object $object): void
    {
        $object->setUpdated(new \DateTime());
        $this->em->persist($object);
        $this->em->flush();
    }
}
