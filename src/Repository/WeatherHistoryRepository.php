<?php

namespace App\Repository;

use App\Entity\WeatherHistory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method WeatherHistory|null find($id, $lockMode = null, $lockVersion = null)
 * @method WeatherHistory|null findOneBy(array $criteria, array $orderBy = null)
 * @method WeatherHistory[]    findAll()
 * @method WeatherHistory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WeatherHistoryRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, WeatherHistory::class);
    }

    public function getCount()
    {
        return $this->createQueryBuilder('w')
            ->select('count(w.temp) as c')
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }

    public function getAggregated()
    {
        return $this->createQueryBuilder('w')
            ->select('MAX(w.temp) as max, MIN(w.temp) as min, AVG(w.temp) as avg')
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }

    public function findByCityGroup()
    {
        return $this->createQueryBuilder('w')
            ->select('w.city, COUNT(w.city) as c')
            ->orderBy('c', 'DESC')
            ->groupBy('w.city')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
            ;
    }
}
