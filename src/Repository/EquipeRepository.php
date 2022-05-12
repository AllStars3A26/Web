<?php

namespace App\Repository;

use App\Entity\Equipe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Equipe|null find($id, $lockMode = null, $lockVersion = null)
 * @method Equipe|null findOneBy(array $criteria, array $orderBy = null)
 * @method Equipe[]    findAll()
 * @method Equipe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EquipeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Equipe::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Equipe $entity, bool $flush = true): void
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
    public function remove(Equipe $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function statistics($type)
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery('select COUNT(e) from App\Entity\Equipe e where e.type_equipe = :type');
        $query->setParameter('type' , $type);
        return $query->getSingleScalarResult();
    }

    public function triByIdCroissant(){
        $em = $this->getEntityManager();
        $query = $em->createQuery('select e from App\Entity\Equipe e ORDER BY e.nbre_joueur');
        return $query->getResult();
    }

    public function triByIdDecroissant(){
        $em = $this->getEntityManager();
        $query = $em->createQuery('select e from App\Entity\Equipe e ORDER BY e.nbre_joueur desc');
        return $query->getResult();
    }

    public function countByNb(){

        $query = $this->getEntityManager()->createQuery("
           SELECT  p.type_equipe as t, count(p.id) as nb FROM App\Entity\Equipe p GROUP BY t
       ");
        return $query->getResult();
    }


    public function findEntitiesByString($str)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT p FROM App:Equipe p WHERE p.nom_equipe LIKE :str or p.type_equipe LIKE :str or p.description_equipe LIKE :str or p.mail_equipe LIKE :str ' )
            ->setParameter('str', '%'.$str.'%')->getResult();
    }

    public function findByNamePopular(string $search = null)
    {
        $queryBuilder = $this->createQueryBuilder('E')

            ->where('E.nom_equipe LIKE :searchTerm')
            ->orWhere('E.type_equipe LIKE :searchTerm')
            ->orWhere('E.description_equipe LIKE :searchTerm')
            ->orWhere('E.mail_equipe LIKE :searchTerm')
            ->setParameter('searchTerm', '%'.$search.'%');


        return $queryBuilder
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }
    // /**
    //  * @return Equipe[] Returns an array of Equipe objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Equipe
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
