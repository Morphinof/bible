<?php

declare(strict_types=1);

namespace Bible\Repository;

use Bible\Entity\Daily;
use DateTimeImmutable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use function Doctrine\ORM\QueryBuilder;

/**
 * @method Daily|null find($id, $lockMode = null, $lockVersion = null)
 * @method Daily|null findOneBy(array $criteria, array $orderBy = null)
 * @method Daily[]    findAll()
 * @method Daily[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DailyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Daily::class);
    }

    public function existsForCurrentDay(): bool
    {
        $today = new DateTimeImmutable();
        $qb = $this->createQueryBuilder('d');
        return $qb
            ->andWhere($qb->expr()->like('d.createdAt', $qb->expr()->literal('%'.$today->format('Y-m-d').'%')))
            ->getQuery()
            ->getOneOrNullResult() !== null;
    }

    // /**
    //  * @return Daily[] Returns an array of Daily objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Daily
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
