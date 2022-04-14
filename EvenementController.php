<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Form\EvenementType;
use App\Repository\EvenementRepository;
use App\Entity\Ticket;
use App\Form\TicketType;
use App\Repository\TicketRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Knp\Component\Pager\PaginatorInterface;
class SeanceController extends AbstractController
{
    /**
     * @Route("/Evenement", name="app_Evenement")
     */
    public function index(): Response
    {
        return $this->render('Evenement/index.html.twig', [
            'controller_name' => 'EvenementController',
        ]);
    }
    /**
     * @Route("/AfficherEvenement/{id_evenement}", name="AfficherEvenement")
     */
    public function AfficherEvenement( $id_evenement)
    {
        $Evenement = $this->getDoctrine()->getRepository(Evenement::class)->findOneBycourscp($id_evenement);
      
        return $this->render('Evenement/listEvenement.html.twig', ["Evenement" => $Evenement]);
    }
/**
     * @Route("/ajouterEvenement/{id_evenement}", name="ajouterEvenement")
     */
    public function ajouterEvenement(Request $request,$id_evenement)
    {
        $Evenement = new Evenement();
        $Evenement->setCoursCp($id_evenement);
        $form = $this->createForm(EvenementType::class, $Evenement);
        
        $form->add("Ajouter", SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            
            $em->persist($Evenement);
            $em->flush();
            return $this->redirectToRoute('listFonction');
        }
        return $this->render("Evenement/AjouterEvenement.html.twig", array('form' => $form->createView()));
    }
     /**
     * @Route("/supprimerEvenement/{id}", name="supprimerEvenement")
     */
    public function supprimerEvenement($id_evenement)
    {
        $Evenement = $this->getDoctrine()->getRepository(Evenement::class)->find($id_evenement);
        $em = $this->getDoctrine()->getManager();
        $em->remove($Evenement);
        $em->flush();
        return $this->redirectToRoute("listEvenement");
    } 

      /**
     * @Route("/modifierEvenement/{id_evenement}", name="modifierEvenement")
     */
    public function modifierEvenement(Request $request, $id_evenement)
    {
        $Evenement = $this->getDoctrine()->getRepository(Evenement::class)->find($id_evenement);
        $form = $this->createForm(EvenementType::class, $Evenement);
        $form->add("Modifier", SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted()&& $form->isValid())  {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('listFonction');
        }
        return $this->render("Evenement/modifier.html.twig", array('form' => $form->createView()));
    }

}
