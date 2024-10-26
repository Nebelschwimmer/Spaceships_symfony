<?php

namespace App\EventListener;

use App\Entity\SpaceShip;
use Doctrine\ORM\Event\PostPersistEventArgs;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use App\Entity\User;
use App\Entity\Publication;


#[AsEntityListener(event: Events::postPersist, entity: Spaceship::class)]

class SpaceShipPostPersist
{

  public function postPersist(Spaceship $spaceship, PostPersistEventArgs $event): void
  {
    $em = $event->getObjectManager();
    $user = $em->getRepository(User::class)->find($spaceship->getUser()->getId());

    $publication = new Publication();
    $publication
      ->setName($spaceship->getName())
      ->setSpaceshipId($spaceship->getId())
      ->setType('space_ship')
      ->setCreatedAt(new \DateTime());


    $user->addPublication($publication);
    $em->persist($publication);
    $em->persist($user);
    $em->flush();

  }

}