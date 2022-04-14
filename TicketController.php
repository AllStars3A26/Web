<?php

namespace App\Controller;
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



class TicketController extends AbstractController
{
    /**
     * @Route("/ticket", name="ticket")
     */
    public function index(): Response
    {
        return $this->render('Ticket/index.html.twig', [
            'controller_name' => 'TicketController',
        ]);
    }
      /**
     * @Route("/listFonction", name="listFonction")
     */
    public function listFonction()
    {
        $ticket = $this->getDoctrine()->getRepository(Ticket::class)->findAll();
      
        return $this->render('Ticket/list.html.twig', ["Ticket" => $Ticket]);
    }
/**
     * @Route("/ajouterTicket", name="ajouterTicket")
     */
    public function ajouterTicket(Request $request)
    {
        $Ticket = new Ticket();
        $form = $this->createForm(TicketType::class, $Ticket);
        $form->add("Ajouter", SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            
            $em->persist($Ticket);
            $em->flush();
            return $this->redirectToRoute('listFonction');
        }
        return $this->render("Ticket/ajouter.html.twig", array('form' => $form->createView()));
    }
     /**
     * @Route("/supprimerTicket/{id_ticket}", name="supprimerTicket")
     */
    public function supprimerTicket($id_ticket)
    {
        $fonction = $this->getDoctrine()->getRepository(Ticket::class)->find($id_ticket);
        $em = $this->getDoctrine()->getManager();
        $em->remove($fonction);
        $em->flush();
        return $this->redirectToRoute("listFonction");
    } 

      /**
     * @Route("/modifierTicket/{id_ticket}", name="modifierTicket")
     */
    public function modifierTicket(Request $request, $id_ticket)
    {
        $Ticket = $this->getDoctrine()->getRepository(Ticket::class)->find($id_ticket);
        $form = $this->createForm(TicketType::class, $Ticket);
        $form->add("Modifier", SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted()&& $form->isValid())  {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('listFonction');
        }
        return $this->render("Ticket/modifier.html.twig", array('form' => $form->createView()));
    }

}
