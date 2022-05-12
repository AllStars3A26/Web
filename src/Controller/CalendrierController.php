<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Repository\SeanceRepository;

class CalendrierController extends AbstractController
{
    /**
     * @Route("/calendrierBack", name="calendrierBack")
     */
    public function index(SeanceRepository $Seance): Response
    {
        $events = $Seance->findAll();
        //dd($events);
        foreach($events as $event){
            $rdvs[] = [
            'id' => $event->getIdSeance(),
            'title' => $event->getNomT(),
            'start' => $event->getDateSeance()->format('Y-m-d'),
            'end' => $event->getDateSeance()->format('Y-m-d '),
            'backgroundColor' => $event->getbackgroundColor(),
            'borderColor' => $event->getborderColor(),
            'textColor' => $event->gettextColor(),
            ];
        }

        $data = json_encode($rdvs);


        //dd($events);
        return $this->render('calendrier/index.html.twig', compact('data'));
    }
     /**
     * @Route("/calendrierFront", name="calendrierFront")
     */
    public function indexFront(SeanceRepository $Seance): Response
    {
        $events = $Seance->findAll();
      //  dd($events);
        foreach($events as $event){
            $rdvs[] = [
            'id' => $event->getIdSeance(),
            'title' => $event->getNomT(),
            'start' => $event->getDateSeance()->format('Y-m-d H:i:s'),
            'end' => $event->getDateSeance()->format('Y-m-d H:i:s'),
            'backgroundColor' => $event->getbackgroundColor(),
            'borderColor' => $event->getborderColor(),
            'textColor' => $event->gettextColor(),
            ];
        }

        $data = json_encode($rdvs);


        
        return $this->render('calendrier/indexFront.html.twig', compact('data'));
    }
}