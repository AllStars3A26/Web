<?php

namespace App\Repository;

use App\Entity\Tournoi;
use App\Entity\ParticipantsTournoi;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use http\QueryString;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query;
/**
 * @method Tournoi|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tournoi|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tournoi[]    findAll()
 * @method Tournoi[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TournoiRepository extends ServiceEntityRepository
{
    public function findByNom($nom)
    {
        $entityManager=$this->getEntityManager();
        $query=$entityManager
            ->createQuery("SELECT r from APP\Entity\Tournoi r where r.nomTournoi like :nom or r.nbParticipants like :nom or r.idTournoi  like :nom")
            ->setParameter('nom','%'.$nom.'%');
        return $query->getResult();
    }
    public function findByIdP($nom)
    {
        $entityManager=$this->getEntityManager();
        $query=$entityManager
            ->createQuery("SELECT p from APP\Entity\ParticipantsTournoi p,APP\Entity\Tournoi t, APP\Entity\Equipe e where t.idTournoi=p.idTournoi and p.idEquipe=e.idEquipe and p.idTournoi like :nom")
            ->setParameter('nom','%'.$nom.'%');
        return $query->getResult();
    }

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tournoi::class);
    }

    public function findByidPer($fan)
    {
        $entityManager=$this->getEntityManager();
        $query=$entityManager
            ->createQuery("SELECT r from APP\Entity\Reclamation r where r.idPer=:fan ")
            ->setParameter('fan',$fan);
        return $query->getResult();
    }
    public function countByNb(){

        $query = $this->getEntityManager()->createQuery("
           SELECT  p.nomTournoi as nom, p.nbParticipants as nb FROM App\Entity\Tournoi p GROUP BY nom
       ");
        return $query->getResult();
    }
    public function findD()
    {
        $entityManager=$this->getEntityManager();
        $query=$entityManager
            ->createQuery("SELECT r.nomTournoi,r.resultatTournoi,r.heure,r.nbParticipants,r.imageTournoi from APP\Entity\Tournoi r ");
        return $query->getResult();
    }
}