<?php

namespace App\Controller;

use App\Entity\Reclamation;
use App\Repository\ReclamationRepository;
use App\Repository\TournoiRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StatisticSaharController extends AbstractController
{
    /**
     * @Route("/statistic", name="app_statistic")
     */
    public function index(): Response
    {
        return $this->render('statistic/stats.html.twig', [
            'controller_name' => 'StatisticSaharController',
        ]);
    }


   /**
    * @Route("/stats", name="rec_stat")
    */
    public function statistiques (TournoiRepository $rep){

        //chercher les types de reclamation

        $tournois = $rep->countByNb();

        $recType = [];
        $recCount = [];


        foreach($tournois as $tournoi){

            //$recType[] = $reclamation->getType();
            $recType[] = $tournoi ['nom'];
            $recCount[]= $tournoi ['nb'];
            //$recCount[] = count($recType);
        }
        return $this->render('statistic/stats.html.twig', [
            'recType' => json_encode($recType),
            'recCount' => json_encode($recCount),


        ]);

        
    }
}
