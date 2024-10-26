<?php

namespace App\Repository;

use App\Entity\SpaceShip;
use App\Filter\SpaceshipFilter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


class SpaceShipRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Spaceship::class);
    }
    public function findByFilter(SpaceshipFilter $spaceshipFilter): array
    {

        $query = $this->createQueryBuilder('s')
            ->where('1 = 1');
        if ($spaceshipFilter->getTitle()) {
            $query
                ->andWhere('s.name LIKE :title')
                ->setParameter('title', '%' . $spaceshipFilter->getTitle() . '%');
        }
        return $query->getQuery()->getResult();
    }
    public function findAllWithQueryParams( int $offset, int $limit, ?string $searchQuery = null): array
    {
        
        $query =  $this->createQueryBuilder('s')->where('1 = 1');
        if ($searchQuery) {
            $query
                ->andWhere('s.name LIKE :searchQuery')
                ->setParameter('searchQuery', '%' . $searchQuery . '%');
        }
        $query
            ->orderBy('s.name', 'ASC')
            ->setMaxResults($limit)
            ->setFirstResult($offset)
        ;

        return $query->getQuery()->getResult();
    }
    public function deleteAll(): bool
    {
        $this->createQueryBuilder('s')
            ->delete()
            ->getQuery()
            ->execute();
        return true;
    }
}
