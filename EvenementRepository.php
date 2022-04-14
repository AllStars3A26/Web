<?php

namespace App\Repository;

use App\Entity\Evenement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Evenement|null find($id_evenement, $lockMode = null, $lockVersion = null)
 * @method Evenement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Evenement[]    findAll()
 * @method Evenement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SeanceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Evenement::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Evenement $entity, bool $flush = true): void
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
    public function remove(Evenement $entity, bool $flush = true): void
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
    public function findOneBycourscp($id_evenement)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT s FROM App:evenement s where s.courCp = :id_evenement' )
            ->setParameter('id_evenement', $id_evenement)
            ->getResult();
    }
    
}
