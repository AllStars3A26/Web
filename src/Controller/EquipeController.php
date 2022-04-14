<?php

namespace App\Controller;
use App\Entity\Equipe;
use App\Form\EquipeType;
use App\Repository\EquipeRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Knp\Component\Pager\PaginatorInterface;



class EquipeController extends AbstractController
{
    /**
     * @Route("/equipe", name="equipe")
     */
    public function index(): Response
    {
        return $this->render('equipe/index.html.twig', [
            'controller_name' => 'EquipeController',
        ]);
    }
      /**
     * @Route("/listFonction", name="listFonction")
     */
    public function listFonction()
    {
        $Equipe = $this->getDoctrine()->getRepository(Equipe::class)->findAll();
      
        return $this->render('equipe/list.html.twig', ["Equipe" => $Equipe]);
    }
/**
     * @Route("/ajouterEquipe", name="ajouterEquipe")
     */
    public function ajouterEquipe(Request $request)
    {
        //$Equipe = new Equipe();
        $Equipe =new Equipe();
        $form = $this->createForm(EquipeType::class, $Equipe);
        $form->add("Ajouter", SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            
            $em->persist($Equipe);
            $em->flush();
            return $this->redirectToRoute('listFonction');
        }
        return $this->render("equipe/ajouter.html.twig", array('form' => $form->createView()));
    }
     /**
     * @Route("/supprimerEquipe/{id}", name="supprimerEquipe")
     */
    public function supprimerEquipe($id)
    {
        $fonction = $this->getDoctrine()->getRepository(Equipe::class)->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($fonction);
        $em->flush();
        return $this->redirectToRoute("listFonction");
    } 

      /**
     * @Route("/modifierEquipe/{id}", name="modifierEquipe")
     */
    public function modifierEquipe(Request $request, $id)
    {
        $Equipe = $this->getDoctrine()->getRepository(Equipe::class)->find($id);
        $form = $this->createForm(EquipeType::class, $Equipe);
        $form->add("Modifier", SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted()&& $form->isValid())  {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('listFonction');
        }
        return $this->render("equipe/modifier.html.twig", array('form' => $form->createView()));
    }
    /**
     * @Route("/listFonctionfront", name="listFonctionfront")
     */
    public function listFonctionfront()
    {
        $Equipe = $this->getDoctrine()->getRepository(Equipe::class)->findAll();

        return $this->render('equipe/listfront.html.twig', ["Equipe" => $Equipe]);
    }

}
