<?php

namespace App\Controller;

use App\Entity\Tournoi;
use App\Form\TournoiType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TournoiController extends AbstractController
{
    /**
     * @Route("/tournoi", name="app_tournoi")
     */
    public function index(): Response
    {
        return $this->render('tournoi/index.html.twig', [
            'controller_name' => 'TournoiController',
        ]);
    }
    /**
     * @Route("/tournoi/liste", name="afficher_tournoi")
     */
    public function afficher(): Response
    {
        $tournois = $this->getDoctrine()
            ->getRepository(Tournoi::class)
            ->findAll();

        return $this->render('tournoi/index.html.twig', [
            'tournois' => $tournois,
        ]);
    }
    /**
     * @Route("/tournoi/listeAdd", name="ajouter_tournoi")
     */
    public function ajouter(Request $request): Response
    {
        $tournoi = new Tournoi();

        $form = $this->createForm(TournoiType::class, $tournoi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('imageTournoi')->getData();
            $filename = md5(uniqid()).'.'.$file->guessExtension();
            $file->move($this->getParameter('uploads_directory'),$filename);
            $tournoi->setImageTournoi($filename);

            $tournoi = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($tournoi);
            $em->flush();

            return $this->redirectToRoute('afficher_tournoi');
        }

        return $this->render('tournoi/ajoutTournoi.html.twig', [
            'tournoi' => $tournoi,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/tournoi/listeDelete/{id}", name="supprimer_tournoi")
     */
    public
    function supprimer($id): Response
    {

        $tournoi = $this->getDoctrine()
            ->getRepository(Tournoi::class)
            ->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($tournoi);
        $em->flush();
        return $this->redirectToRoute('afficher_tournoi');
    }

    /**
     * @Route("/tournoi/listeModif/{id}", name="modifier_tournoi")
     */
    public function modifier($id,Request $request): Response
    {
        $tournoi = $this->getDoctrine()
            ->getRepository(Tournoi::class)
            ->find($id);

        $form = $this->createForm(TournoiType::class, $tournoi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();

            $em->flush();

            return $this->redirectToRoute('afficher_tournoi');
        }

        return $this->render('tournoi/modifierTournoi.html.twig', [
            'tournoi' => $tournoi,
            'form' => $form->createView(),
        ]);
    }
}
