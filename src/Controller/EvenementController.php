<?php

namespace App\Controller;
use App\Entity\Evenement;
use App\Form\EvenementType;
use App\Repository\EvenementRepository;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class EvenementController extends AbstractController
{
    /**
     * @Route("/evenement", name="app_evenement")
     */
    public function index(): Response
    {
        return $this->render('evenement/index.html.twig', [
            'controller_name' => 'EvenementController',
        ]);
    }


 /**
     * @Route("/listEvenement", name="listEvenement")
     */
    public function listEvenement()
    {
        $evenement = $this->getDoctrine()->getRepository(Evenement::class)->findAll();

        return $this->render('Evenement/list.html.twig', ["Evenement" => $evenement]);
    }
/**
     * @Route("/ajouterEvenement", name="ajouterEvenement")
     */
    public function ajouterEvenement(Request $request)
    {
        $Evenement = new Evenement();
        $form = $this->createForm(EvenementType::class, $Evenement);
        $form->add("Ajouter", SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->persist($Evenement);
            $em->flush();
            return $this->redirectToRoute('listEvenement');
        }
        return $this->render("Evenement/ajouter.html.twig", array('form' => $form->createView()));
    }
     /**
     * @Route("/supprimerEvenement/{id}", name="supprimerEvenement")
     */
    public function supprimerEvenement($id)
    {
        $fonction = $this->getDoctrine()->getRepository(Evenement::class)->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($fonction);
        $em->flush();
        return $this->redirectToRoute("listEvenement");
    }

      /**
     * @Route("/modifierEvenement/{id}", name="modifierEvenement")
     */
    public function modifierEvenement(Request $request, $id)
    {
        $Evenement = $this->getDoctrine()->getRepository(Evenement::class)->find($id);
        $form = $this->createForm(EvenementType::class, $Evenement);
        $form->add("Modifier", SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted()&& $form->isValid())  {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('listEvenement');
        }
        return $this->render("Evenement/modifier.html.twig", array('form' => $form->createView()));
    }






}
