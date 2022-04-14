<?php

namespace App\Controller;
use App\Entity\Cours;
use App\Form\CoursType;
use App\Repository\CoursRepository;
use App\Entity\Seance;
use App\Form\SeanceType;
use App\Repository\SeanceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Knp\Component\Pager\PaginatorInterface;



class CoursController extends AbstractController
{
    /**
     * @Route("/cours", name="cours")
     */
    public function index(): Response
    {
        return $this->render('cours/index.html.twig', [
            'controller_name' => 'CoursController',
        ]);
    }
      /**
     * @Route("/listFonction", name="listFonction")
     */
    public function listFonction()
    {
        $Cours = $this->getDoctrine()->getRepository(Cours::class)->findAll();
      
        return $this->render('cours/list.html.twig', ["Cours" => $Cours]);
    }
     /**
     * @Route("/listFonctionfront", name="listFonctionfront")
     */
    public function listFonctionfront()
    {
        $Cours = $this->getDoctrine()->getRepository(Cours::class)->findAll();
      
        return $this->render('cours/listfront.html.twig', ["Cours" => $Cours]);
    }
/**
     * @Route("/ajouterCours", name="ajouterCours")
     */
    public function ajouterCours(Request $request)
    {
        $Cours = new Cours();
        $form = $this->createForm(CoursType::class, $Cours);
        $form->add("Ajouter", SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            
            $em->persist($Cours);
            $em->flush();
            return $this->redirectToRoute('listFonction');
        }
        return $this->render("cours/ajouter.html.twig", array('form' => $form->createView()));
    }
     /**
     * @Route("/supprimerCours/{id}", name="supprimerCours")
     */
    public function supprimerCours($id)
    {
        $fonction = $this->getDoctrine()->getRepository(Cours::class)->find($id);
        
        $em = $this->getDoctrine()->getManager();
    
        $em->remove($fonction);
               
        $em->flush();
        return $this->redirectToRoute("listFonction");

    } 

      /**
     * @Route("/modifierCours/{id}", name="modifierCours")
     */
    public function modifierCours(Request $request, $id)
    {
        $Cours = $this->getDoctrine()->getRepository(Cours::class)->find($id);
        $form = $this->createForm(CoursType::class, $Cours);
        $form->add("Modifier", SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted()&& $form->isValid())  {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('listFonction');
        }
        return $this->render("cours/modifier.html.twig", array('form' => $form->createView()));
    }

}
