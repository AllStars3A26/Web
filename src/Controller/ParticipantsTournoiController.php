<?php

namespace App\Controller;

use App\Entity\ParticipantsTournoi;
use App\Form\ParticipantsTournoiType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ParticipantsTournoiController extends AbstractController
{
    /**
     * @Route("/participants/tournoi", name="app_participants_tournoi")
     */
    public function index(): Response
    {
        return $this->render('participants_tournoi/index.html.twig', [
            'controller_name' => 'ParticipantsTournoiController',
        ]);
    }
    /**
     * @Route("/participants/tournoi/liste", name="afficher_ptournoi")
     */
    public function afficher(): Response
    {
        $tournois = $this->getDoctrine()
            ->getRepository(ParticipantsTournoi::class)
            ->findAll();

        return $this->render('participants_tournoi/index.html.twig', [
            'ptournois' => $tournois,
        ]);
    }
    /**
     * @Route("/participants/tournoi/listeAdd", name="ajouter_ptournoi")
     */
    public function ajouter(Request $request): Response
    {
        $tournoi = new ParticipantsTournoi();

        $form = $this->createForm(ParticipantsTournoiType::class, $tournoi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $tournoi = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($tournoi);
            $em->flush();

            return $this->redirectToRoute('afficher_ptournoi');
        }

        return $this->render('participants_tournoi/ajoutTournoi.html.twig', [
            'tournoi' => $tournoi,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/participants/tournoi/listeDelete/{id}", name="supprimer_ptournoi")
     */
    public
    function supprimer($id): Response
    {

        $tournoi = $this->getDoctrine()
            ->getRepository(ParticipantsTournoi::class)
            ->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($tournoi);
        $em->flush();
        return $this->redirectToRoute('pafficher_tournoi');
    }

    /**
     * @Route("/participants/tournoi/listeModif/{id}", name="modifier_ptournoi")
     */
    public function modifier($id,Request $request): Response
    {
        $tournoi = $this->getDoctrine()
            ->getRepository(ParticipantsTournoi::class)
            ->find($id);

        $form = $this->createForm(ParticipantsTournoiType::class, $tournoi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();

            $em->flush();

            return $this->redirectToRoute('afficher_ptournoi');
        }

        return $this->render('participants_tournoi/modifierTournoi.html.twig', [
            'ptournoi' => $tournoi,
            'form' => $form->createView(),
        ]);
    }
}
