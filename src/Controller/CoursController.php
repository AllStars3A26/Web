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
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;



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
     * @Route("/listFonction2", name="listFonction2")
     */
    public function listFonction()
    {
        $Cours = $this->getDoctrine()->getRepository(Cours::class)->findAll();
      
        return $this->render('cours/list.html.twig', ["Cours" => $Cours]);
    }
     /**
     * @Route("/listFonctionfront2", name="listFonctionfront2")
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
            return $this->redirectToRoute('listFonction2');
        }
        return $this->render("cours/ajouter.html.twig", array('formCours' => $form->createView()));
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
        return $this->redirectToRoute("listFonction2");

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
            return $this->redirectToRoute('listFonction2');
        }
        return $this->render("cours/modifier.html.twig", array('formCoursM' => $form->createView()));
    }
  

  /**
 * Search action.
 * @Route("/search", name="ajax_search")
 * @param  Request               $request Request instance
 * @param  string                $search  Search term
 * @return Response|JsonResponse          Response instance
 */
   public function searchAction(Request $request)
  {
      $em = $this->getDoctrine()->getManager();
      $requestString = $request->get('q');
      
      $Cours = $em->getRepository(Cours::class)->findEntitiesByString($requestString);
      
      
      if (!$Cours) {
          $result['cours']['error'] = "product introuvable 🙁 ";
      } else {
          $result['Cours'] = $this->getRealEntities($Cours);
      }
      
      return new Response(json_encode($result));
      
  }

  public function getRealEntities($Cours){

    foreach ($Cours as $Cours){
        $realEntities[$Cours->getIdCours()] = [$Cours->getTitre() ,$Cours->getNome(),$Cours->getType()];
    }

    return $realEntities;
}

/**
     * @Route("/detailCours/{id}", name="detailCours")
     */
    public function DetailCours($id)
    {
        $Cours = $this->getDoctrine()->getRepository(Cours::class)->find($id);

        return $this->render('cours/detail1.html.twig', ["Cours" => $Cours]);
    }
    /**
     * @Route("/detailCoursFront/{id}", name="detailCours")
     */
    public function DetailCoursFront($id)
    {
        $Cours = $this->getDoctrine()->getRepository(Cours::class)->find($id);

        return $this->render('cours/detailfront.html.twig', ["Cours" => $Cours]);
    }
  /*******************JSON***********************/
  /**
    * @Route("/ajouterCoursJSON",name="ajouterCoursJSON")
    */

    public function ajouterCoursJSON(Request $request,NormalizerInterface $Normalizer)
    {
	    $em = $this->getDoctrine()->getManager();
        $Cours = new Cours();
        $Cours->setTitre($request->get('titre'));
        $Cours->setNome($request->get('nome'));
        $Cours->setType($request->get('type'));
        $Cours->setNbHeure(0);
        $Cours->setImc($request->get('imc'));
        $em->persist($Cours);
        $em->flush();
        $jsonContent = $Normalizer->normalize($Cours, 'json',['groups'=>'post:read']);
        return new Response(json_encode($jsonContent));;
    }
        /**
    * @Route("/listCoursJSON", name="listCoursJSON")
    */
    public function getCoursJSON(NormalizerInterface $Normalizer)
    {   


$Cours=$this->getDoctrine()->getManager()->getRepository( Cours::class)->findAll();
$serializer=new Serializer([new ObjectNormalizer()]);
$formatted=$serializer->normalize($Cours);
return new JsonResponse($formatted);


       
    }
      /**
    * @Route("/deleteCoursJSON", name="deleteCoursJSON")
    */
    public function deleteCoursJSON(Request $request)

    {$titre=$request->get("titre");
        $em=$this->getDoctrine()->getManager();
        $cours=$em->getRepository(Cours::class)->findOneByTitre($titre);
        if($cours!=null)
        {$em->remove($cours);
            $em->flush();
            $serialize=new Serializer([new ObjectNormalizer()]);
            $formatted=$serialize->normalize("cours supprimé avec succés");
            return new JsonResponse($formatted);



        }

return new JsonResponse("id invalide");
 }  
    /**
    * @Route("/updateCoursJSON", name="updateCoursJSON")
    */
    public function updateCoursJSON(Request $request)

    {  
        $titre=$request->get('titre');
        //dd($titre);
        $em=$this->getDoctrine()->getManager();
        $Cours=$em->getRepository(Cours::class)->findOneByTitre($titre);
       
      //  $Cours->setTitre($request->get('titre'));
        $Cours->setNome($request->get('nome'));
        $Cours->setType($request->get('type'));
       // $Cours->setNbHeure($request->get('nb_heure'));
        $Cours->setImc($request->get('imc'));
        $em->persist($Cours);
            $em->flush();
            $serialize=new Serializer([new ObjectNormalizer()]);
            $formatted=$serialize->normalize("cours modifie avec succés");
            return new JsonResponse($formatted);

    }

}
