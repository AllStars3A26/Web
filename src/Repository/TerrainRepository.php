<?php

namespace App\Repository;

use App\Entity\Terrain;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Terrain|null find($id, $lockMode = null, $lockVersion = null)
 * @method Terrain|null findOneBy(array $criteria, array $orderBy = null)
 * @method Terrain[]    findAll()
 * @method Terrain[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TerrainRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Terrain::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Terrain $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Terrain $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return Terrain[] Returns an array of Terrain objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Terrain
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findByEquipeCp($id)
    {
        
        return $this->getEntityManager()
            ->createQuery('SELECT s FROM App:Terrain s where s.id_equipe = :id' )
            ->setParameter('id', $id)
            ->getResult();
    }
}
