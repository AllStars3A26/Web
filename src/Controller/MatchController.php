<?php

namespace App\Controller;

use App\Entity\Matchh;
use App\Form\MatchType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MatchController extends AbstractController
{
    /**
     * @Route("/match", name="app_match")
     */
    public function index(): Response
    {
        return $this->render('match/index.html.twig', [
            'controller_name' => 'MatchController',
        ]);
    }
    /**
     * @Route("/match/liste", name="afficher_match")
     */
    public function afficher(): Response
    {
        $matchs = $this->getDoctrine()
            ->getRepository(Matchh::class)
            ->findAll();

        return $this->render('match/index.html.twig', [
            'matchs' => $matchs,
        ]);
    }
    /**
     * @Route("/match/listef", name="afficher_matchf")
     */
    public function afficherf(): Response
    {
        $matchs = $this->getDoctrine()
            ->getRepository(Matchh::class)
            ->findAll();
   $date = new \DateTime('now');
        return $this->render('frontMatchbase.html.twig', [
            'matchs' => $matchs,
            'date'=>$date
        ]);
    }
    /**
     * @Route("/match/listeAdd", name="ajouter_match")
     */
    public function ajouter(Request $request): Response
    {
        $match = new Matchh();

        $form = $this->createForm(MatchType::class, $match);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $match = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($match);
            $em->flush();

            return $this->redirectToRoute('afficher_match');
        }

        return $this->render('match/ajoutMatch.html.twig', [
            'match' => $match,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/match/listeDelete/{id}", name="supprimer_match")
     */
    public
    function supprimer($id): Response
    {

        $tournoi = $this->getDoctrine()
            ->getRepository(Matchh::class)
            ->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($tournoi);
        $em->flush();
        return $this->redirectToRoute('afficher_match');
    }

    /**
     * @Route("/match/listeModif/{id}", name="modifier_match")
     */
    public function modifier($id,Request $request): Response
    {
        $match = $this->getDoctrine()
            ->getRepository(Matchh::class)
            ->find($id);

        $form = $this->createForm(MatchType::class, $match);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();

            $em->flush();

            return $this->redirectToRoute('afficher_match');
        }

        return $this->render('match/modifierMatch.html.twig', [
            'match' => $match,
            'form' => $form->createView(),
        ]);
    }
}
