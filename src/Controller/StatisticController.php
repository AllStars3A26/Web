<?php

namespace App\Controller;

use App\Entity\Equipe;
use App\Repository\EquipeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StatisticController extends AbstractController
{
    /**
     * @Route("/statistic", name="app_statistic")
     */
    public function index(): Response
    {
        return $this->render('statistic/stats.html.twig', [
            'controller_name' => 'StatisticController',
        ]);
    }


   /**
    * @Route("/stats1", name="rec_stat1")
    */
    public function statistiques (EquipeRepository $rep){

        //chercher les types de reclamation

        $hbgs = $rep->countByNb();

        $recType = [];
        $recCount = [];


        foreach($hbgs as $hbg){

            //$recType[] = $hbg->getTypeEquipe();
            $recType[] = $hbg ['t'];
            $recCount[]= $hbg ['nb'];
            //$recCount[] = count($recType);
        }
        return $this->render('statistic/stats.html.twig', [
            'recType' => json_encode($recType),
            'recCount' => json_encode($recCount),


        ]);

        
    }
}