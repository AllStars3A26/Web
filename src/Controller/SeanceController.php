<?php

namespace App\Controller;

use App\Entity\Seance;
use App\Form\SeanceType;
use App\Repository\SeanceRepository;
use App\Entity\Cours;
use App\Form\CoursType;
use App\Repository\CoursRepository;

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
     * @Route("/seance", name="app_seance")
     */
    public function index(): Response
    {
        return $this->render('seance/index.html.twig', [
            'controller_name' => 'SeanceController',
        ]);
    }
    /**
     * @Route("/AfficherSeance/{id}", name="AfficherSeance")
     */
    public function AfficherSeance( $id)
    {
        $Seance = $this->getDoctrine()->getRepository(Seance::class)->findOneBycoursCp($id);
      
        return $this->render('seance/listseance.html.twig', ["Seances" => $Seance]);
    }
/**
     * @Route("/ajouterSeance/{id}", name="ajouterSeance")
     */
    public function ajouterSeance(Request $request,$id)
    {
        $Seance = new Seance();
        $Seance->setCoursCp($id);
        $form = $this->createForm(SeanceType::class, $Seance);
        
        $form->add("Ajouter", SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            
            $em->persist($Seance);
            $em->flush();
            return $this->redirectToRoute('listFonction');
        }
        return $this->render("seance/AjouterSeance.html.twig", array('form' => $form->createView()));
    }
     /**
     * @Route("/supprimerSeance/{id}", name="supprimerSeance")
     */
    public function supprimerSeance($id)
    {
        $Seance = $this->getDoctrine()->getRepository(Seance::class)->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($Seance);
        $em->flush();
        return $this->redirectToRoute("listSeance");
    } 

      /**
     * @Route("/modifierSeance/{id}", name="modifierSeance")
     */
    public function modifierSeance(Request $request, $id)
    {
        $Seance = $this->getDoctrine()->getRepository(Seance::class)->find($id);
        $form = $this->createForm(SeanceType::class, $Seance);
        $form->add("Modifier", SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted()&& $form->isValid())  {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('listFonction');
        }
        return $this->render("Seance/modifierSeance.html.twig", array('form' => $form->createView()));
    }

}
