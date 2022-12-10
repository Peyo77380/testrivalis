<?php

namespace App\Repository;

use App\Entity\DataToConvert;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DataToConvert>
 *
 * @method DataToConvert|null find($id, $lockMode = null, $lockVersion = null)
 * @method DataToConvert|null findOneBy(array $criteria, array $orderBy = null)
 * @method DataToConvert[]    findAll()
 * @method DataToConvert[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DataToConvertRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DataToConvert::class);
    }

    public function save(DataToConvert $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(DataToConvert $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findByUser(User $user)
    {
        return $this->createQueryBuilder('d')
            ->where('d.user = :user')
            ->setParameter('user', $user->getId())
            ->getQuery()
            ->getResult();
    }
    
//    /**
//     * @return DataToConvert[] Returns an array of DataToConvert objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('d.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?DataToConvert
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
