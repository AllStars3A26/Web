<?php

namespace App\Repository;


use App\Entity\ParticipantsTournoi;
use App\Entity\Tournoi;
use App\Entity\Equipe;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use http\QueryString;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query;
/**
 * @method ParticipantsTournoi|null find($id, $lockMode = null, $lockVersion = null)
 * @method ParticipantsTournoi|null findOneBy(array $criteria, array $orderBy = null)
 * @method ParticipantsTournoi[]    findAll()
 * @method ParticipantsTournoi[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PTournoiRepository extends ServiceEntityRepository
{

    public function findByIdP($nom)
    {
        $entityManager=$this->getEntityManager();
        $query=$entityManager
            ->createQuery("SELECT p from APP\Entity\ParticipantsTournoi p where p.idTournoi = :nom")
            ->setParameter('nom',$nom);
        return $query->getResult();
    }

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ParticipantsTournoi::class);
    }


}