<?php

namespace App\Controller;
use App\Entity\Terrain;
use PHPUnit\Util\Json;
use App\Entity\Equipe;
use App\Form\TerrainType;
use App\Repository\TerrainRepository;
use App\Form\EquipeType;
use App\Repository\EquipeRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Validator\Constraints\DateTime;


class TerrainController extends AbstractController
{
      /*******************JSON***********************/
  /**
    * @Route("/ajouterterrainJSON",name="ajouterterrainJSON")
    */

    public function ajouterterrainJSON(Request $request,NormalizerInterface $Normalizer)
    {
	    $em = $this->getDoctrine()->getManager();
        $terrain = new Terrain();
        $id_equipe=$request->get('id_equipe');
        $terrain->setIdEquipe($this->getDoctrine()->getManager()->getRepository(Equipe::class)->Find($id_equipe));
        $terrain->setNomTerrain($request->get('nom'));
        $terrain->setTypeTerrain($request->get('type'));
        $terrain->setDescriptionTerrain($request->get('description'));
        $terrain->setAdresseTerrain($request->get('adresse'));
        $terrain->setDisponibilite($request->get('disponibilite'));
        $em->persist($terrain);
        $em->flush();
        $jsonContent = $Normalizer->normalize($terrain, 'json',['groups'=>'post:read']);
        return new Response(json_encode($jsonContent));;
    }
        /**
    * @Route("/Allterrain", name="Allterrain")
    */
    public function getterrainJSON(NormalizerInterface $Normalizer)
    {   


        $terrain=$this->getDoctrine()->getManager()->getRepository(Terrain::class)->findAll();

        $jsonContent = $Normalizer->normalize($terrain, 'json', ['groups' => 'post:read']);
        return new Response(json_encode($jsonContent));


       
    }
      /**
    * @Route("/deleteterrain", name="deleteterrain")
    */
    public function deleteterrainJSON(Request $request)

    {$id=$request->get("id");
        $em=$this->getDoctrine()->getManager();
        $terrain=$em->getRepository(Terrain::class)->find($id);
        if($terrain!=null)
        {$em->remove($terrain);
            $em->flush();
            $serialize=new Serializer([new ObjectNormalizer()]);
            $formatted=$serialize->normalize("terrain supprimé avec succés");
            return new JsonResponse($formatted);



        }

return new JsonResponse("id invalide");
 }  
    /**
    * @Route("/updateterrain1", name="updateterrain1")
    */
    public function updateterrainJSON(Request $request)

    {  
        $id=$request->get("id");
        $em=$this->getDoctrine()->getManager();
        $terrain = new Terrain();
        $terrain->setNomTerrain($request->get('nom'));
        $terrain->setTypeTerrain($request->get('type'));
        $terrain->setDescriptionTerrain($request->get('description'));
        $terrain->setAdresseTerrain($request->get('adresse'));
        $terrain->setDisponibilite($request->get('disponibilite'));
        $em->persist($terrain);
            $em->flush();
            $serialize=new Serializer([new ObjectNormalizer()]);
            $formatted=$serialize->normalize("terrain modifie avec succés");
            return new JsonResponse($formatted);

    }

 /**
     * @Route("/Terrain", name="app_terrain")
     */
    public function index(): Response
    {
        return $this->render('Terrain/index.html.twig', [
            'controller_name' => 'TerrainController',
        ]);
    }
    /**
     * @Route("/AfficherTerrain/{id}", name="AfficherTerrain")
     */
    
    public function AfficherTerrain( Request $request,$id,PaginatorInterface $paginator)
    {$Equipe=$this->getDoctrine()->getRepository(Equipe::class)->find($id);
        
        
        $Terrain = $this->getDoctrine()->getRepository(Terrain::class)->findByEquipeCp($Equipe->getId());
        $Terrain=$paginator->paginate(
            $Terrain,
            $request->query->getInt('page',1),
            3

        );
      
        return $this->render('Terrain/listTerrain.html.twig', ["Terrain" => $Terrain]);
    }
       /**
     * @Route("/AfficherTerrainFront/{id}", name="AfficherTerrainFront")
     */
    public function AfficherTerrainFront(Request $request, $id,PaginatorInterface $paginator)
    {$Equipe=$this->getDoctrine()->getRepository(Equipe::class)->find($id);
        
        $Terrain = $this->getDoctrine()->getRepository(Terrain::class)->findByEquipeCp($Equipe->getId());
        $Terrain=$paginator->paginate(
            $Terrain,
            $request->query->getInt('page',1),
            3

        );
        return $this->render('Terrain/listTerrainfront.html.twig', ["Terrain" => $Terrain]);
    }
/**
     * @Route("/ajouterTerrain/{id}", name="ajouterTerrain")
     */
    public function ajouterTerrain(Request $request,$id)
    {$Equipe=$this->getDoctrine()->getRepository(Equipe::class)->find($id);
        $Terrain = new Terrain();
        $Terrain->setIdEquipe($Equipe);
        $form = $this->createForm(TerrainType::class, $Terrain);
        
        $form->add("Ajouter", SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            
            $em->persist($Terrain);
            $em->flush();
            return $this->redirectToRoute('listFonction');
        }
        return $this->render("Terrain/AjouterTerrain.html.twig", array('formTerrain' => $form->createView()));
    }
     /**
     * @Route("/supprimerTerrain/{id}", name="supprimerTerrain")
     */
    public function supprimerTerrain($id)
    { 
        $Terrain = $this->getDoctrine()->getRepository(Terrain::class)->find($id);
            $em = $this->getDoctrine()->getManager();
            $em->remove($Terrain);
            $em->flush();
            return $this->redirectToRoute("listFonction");
            
    } 


      /**
     * @Route("/modifierTerrain/{id}", name="modifierTerrain")
     */
    public function modifierTerrain(Request $request, $id)
    {
        $Terrain = $this->getDoctrine()->getRepository(Terrain::class)->find($id);
        $form = $this->createForm(TerrainType::class, $Terrain);
        $form->add("Modifier", SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted()&& $form->isValid())  {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('listFonction');
        }
        return $this->render("Terrain/modifierTerrain.html.twig", array('formTerrainM' => $form->createView()));
    }
     /**
     * @Route("/participerTerrain/{id}", name="participerTerrain")
     */
    function Participer(Request $request, $id,\Swift_Mailer $mailer)
{ $Terrain = $this->getDoctrine()->getRepository(Terrain::class)->find($id);
    $Terrain->setNbParticipants($Terrain->getNbParticipants() + 1);
    $this->getDoctrine()->getManager()->flush();
    
    $message = (new \Swift_Message('Confirmation de participation'))
    ->setFrom('skanderhelmisportify@gmail.com')
    ->setContentType("text/html")
    ->setTo('emnatrabelsi611@gmail.com')
    //->setTo('$user->getMail()')
    ->setBody(
     "<p style='color: black;'> Votre demande de participation au Equipe est confirmée</p>"

    );
    
    $mailer->send($message);
    $sid = "AC67f52567b92218cc3d58ce3232ad4fff"; // Your Account SID from www.twilio.com/console
$token = "ce46bde5fe24e5e18e165c001d2050d9"; // Your Auth Token from www.twilio.com/console

$client = new Client($sid, $token);
$message = $client->messages->create(

  '+21620742823', // Text this number
  [
    'from' => '+19379187956', // From a valid Twilio number
    'body' => ' Votre demande de participation au Equipe est confirmée'
  ]
);
print $message->sid;
echo '<script language="javascript">';
echo 'alert("Participation faite avec succès, vous recevrez sous peu un SMS ainsi qu un email qui confirmera votre participatipation")';
echo '</script>';



 

 
    return $this->redirectToRoute('listFonction');

}
}
