<?php

namespace App\Controller;

use App\Entity\Seance;
use App\Form\SeanceType;
use App\Repository\SeanceRepository;
use App\Entity\Cours;
use App\Form\CoursType;
use App\Repository\CoursRepository;
use \Twilio\Rest\Client;



use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;


use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;





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
    
{$cours=$this->getDoctrine()->getRepository(Cours::class)->find($id);
        
        
        $Seance = $this->getDoctrine()->getRepository(Seance::class)->findBycoursCp($cours->getIdCours());
   
      
        return $this->render('seance/listseance.html.twig', ["Seances" => $Seance]);
    }
       /**
     * @Route("/AfficherSeanceFront/{id}", name="AfficherSeanceFront")
     */
    public function AfficherSeanceFront( $id)
    {$cours=$this->getDoctrine()->getRepository(Cours::class)->find($id);
        
        $Seance = $this->getDoctrine()->getRepository(Seance::class)->findBycoursCp($cours->getIdCours());
      
        return $this->render('seance/listseancefront.html.twig', ["Seances" => $Seance]);
    }
/**
     * @Route("/ajouterSeance/{id}", name="ajouterSeance")
     */
    public function ajouterSeance(Request $request,$id)
    {$cours=$this->getDoctrine()->getRepository(Cours::class)->find($id);
        $Seance = new Seance();
        $Seance->setCoursCp($cours);
        $form = $this->createForm(SeanceType::class, $Seance);
        
        $form->add("Ajouter", SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            
            $em->persist($Seance);
            $em->flush();
            return $this->redirectToRoute('listFonction');
        }
        return $this->render("seance/AjouterSeance.html.twig", array('formSeance' => $form->createView()));
    }
     /**
     * @Route("/supprimerSeance/{id}", name="supprimerSeance")
     */
    public function supprimerSeance($id)
    { 
        $Seance = $this->getDoctrine()->getRepository(Seance::class)->find($id);
        if($Seance->getNbParticipants()>=5)
        {
            echo "<script>alert('Vous ne pouvez supprimer une séance ayant 5 participants ou plus ! ');</script>";
          
        }
        else{
            $em = $this->getDoctrine()->getManager();
            $em->remove($Seance);
            $em->flush();
            return $this->redirectToRoute("listFonction");}
            return $this->redirectToRoute("listFonction");
            
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
        return $this->render("Seance/modifierSeance.html.twig", array('formSeanceM' => $form->createView()));
    }
     /**
     * @Route("/participerSeance/{id}", name="participerSeance")
     */
    function Participer(Request $request, $id,\Swift_Mailer $mailer)
{ $Seance = $this->getDoctrine()->getRepository(Seance::class)->find($id);
    $Seance->setNbParticipants($Seance->getNbParticipants() + 1);
    $this->getDoctrine()->getManager()->flush();
    
    $message = (new \Swift_Message('Confirmation de participation'))
    ->setFrom('skanderhelmisportify@gmail.com')
    ->setContentType("text/html")
    ->setTo('emnatrabelsi611@gmail.com')
    //->setTo('$user->getMail()')
    ->setBody(
     "<p style='color: black;'> Votre demande de participation au cours est confirmée</p>"

    );
    
    $mailer->send($message);
    $sid = "AC67f52567b92218cc3d58ce3232ad4fff"; // Your Account SID from www.twilio.com/console
$token = "ce46bde5fe24e5e18e165c001d2050d9"; // Your Auth Token from www.twilio.com/console

$client = new Client($sid, $token);
$message = $client->messages->create(

  '+21620742823', // Text this number
  [
    'from' => '+19379187956', // From a valid Twilio number
    'body' => ' Votre demande de participation au cours est confirmée'
  ]
);
print $message->sid;
echo '<script language="javascript">';
echo 'alert("Participation faite avec succès, vous recevrez sous peu un SMS ainsi qu un email qui confirmera votre participatipation")';
echo '</script>';



 

 
    return $this->redirectToRoute('listFonction');

}
 /*******************JSON***********************/
  /**
    * @Route("/ajouterSeanceJSON",name="ajouterSeanceJSON")
    */

    public function ajouterSeanceJSON(Request $request,NormalizerInterface $Normalizer)
    {
	    $em = $this->getDoctrine()->getManager();
        $Seance = new Seance();
      //  $Seance->setDateSeance($request->get('date_seance'));
        $Seance->setHeureSeance($request->get('heureSeance'));
        $Seance->setNomT($request->get('nomT'));
        $Seance->setNomE($request->get('nomE'));
        //$Seance->setCoursCp($request->get('cours_cp'));
        $em->persist($Seance);
        $em->flush();
        $jsonContent = $Normalizer->normalize($Seance, 'json',['groups'=>'post:read']);
        return new Response(json_encode($jsonContent));;
    }
        /**
    * @Route("/listSeanceJSON", name="listSeanceJSON")
    */
    public function getSeanceJSON(NormalizerInterface $Normalizer)
    {   


        $Seance=$this->getDoctrine()->getManager()->getRepository( Seance::class)->findAll();
        $serializer=new Serializer([new ObjectNormalizer()]);
        $formatted=$serializer->normalize($Seance);
        return new JsonResponse($formatted);


       
    }
      /**
    * @Route("/deleteSeanceJSON", name="deleteSeanceJSON")
    */
    public function deleteSeanceJSON(Request $request)

    {$titre=$request->get("nom_T");
        $em=$this->getDoctrine()->getManager();
        $Seance=$em->getRepository(Seance::class)->findOneByNomT($titre);
        if($Seance!=null)
        {$em->remove($Seance);
            $em->flush();
            $serialize=new Serializer([new ObjectNormalizer()]);
            $formatted=$serialize->normalize("Séance supprimé avec succés");
            return new JsonResponse($formatted);



        }

return new JsonResponse("id invalide");
 }  
    /**
    * @Route("/updateSeanceJSON", name="updateSeanceJSON")
    */
    public function updateSeanceJSON(Request $request)

    {  
        $titre=$request->get("nom_T");
        $em=$this->getDoctrine()->getManager();
        $Seance=$em->getRepository(Seance::class)->findOneByNomT($titre);
      //  $Seance->setDateSeance($request->get('date_seance'));
        $Seance->setHeureSeance($request->get('heureSeance'));
      //  $Seance->setNomT($request->get('nom_T'));
        $Seance->setNomE($request->get('nomE'));
      //  $Seance->setCoursCp($request->get('cours_cp'));
        $em->persist($Seance);
            $em->flush();
            $serialize=new Serializer([new ObjectNormalizer()]);
            $formatted=$serialize->normalize("Seance modifie avec succés");
            return new JsonResponse($formatted);

    }


}
