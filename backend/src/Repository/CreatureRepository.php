<?php

namespace App\Repository;

use App\Entity\CreatureStatBlock;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CreatureStatBlock>
 *
 * @method CreatureStatBlock|null find($id, $lockMode = null, $lockVersion = null)
 * @method CreatureStatBlock|null findOneBy(array $criteria, array $orderBy = null)
 * @method CreatureStatBlock[]    findAll()
 * @method CreatureStatBlock[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CreatureStatBlockRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CreatureStatBlock::class);
    }

//    /**
//     * @return CreatureStatBlock[] Returns an array of CreatureStatBlock objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?CreatureStatBlock
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
