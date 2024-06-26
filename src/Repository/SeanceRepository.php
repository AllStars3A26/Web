<?php

namespace App\Repository;

use App\Entity\Seance;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Seance|null find($id, $lockMode = null, $lockVersion = null)
 * @method Seance|null findOneBy(array $criteria, array $orderBy = null)
 * @method Seance[]    findAll()
 * @method Seance[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SeanceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Seance::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Seance $entity, bool $flush = true): void
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
    public function remove(Seance $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }
   /* public function findByidcours($valeur): 
    {
$repository = $this->getDoctrine()
                   ->getManager()
                   ->getRepository('pidev:seance');
$listeSeances = $repository->findByidcours($valeur);
}*/

    // /**
    //  * @return Seance[] Returns an array of Seance objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    
   /* public function findOneBycourscp($value): ?Seance
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.coursCp.getIdCours() = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult();
        
        
    }*/
    public function findBycoursCp($id)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT s FROM App:seance s where s->coursCp = :id' )
            ->setParameter('id', $id)
            ->getResult();
    }
    public function findOneByNomT($value): ?Cours
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.nom_T = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    
}
