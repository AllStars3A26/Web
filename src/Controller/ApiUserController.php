<?php

namespace App\Controller;
use App\Entity\Cours;
use App\Entity\Equipe;
use App\Entity\Evenement;
use App\Entity\Produit;
use App\Entity\Seance;
use App\Entity\Terrain;
use App\Entity\Ticket;
use App\Entity\Tournoi;
use App\Entity\User;
use App\Repository\TournoiRepository;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Util\Json;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class ApiUserController extends AbstractController
{

    /**
     * @Route("/apiuser/user", name="app_api_user")
     */
    public function index(): Response
    {
        return $this->render('api_user/index.html.twig', [
            'controller_name' => 'ApiUserController',
        ]);
    }

    /**
     * @Route("/apiuser/signup", name="api_signup")
     */
    public function signup(Request $request , UserPasswordEncoderInterface $passwordEncoder)
    {
        $email = $request->query->get("email");
        $username = $request->query->get("username");
        $password = $request->query->get("password");
        $full_name = $request->query->get("full_name");
        $adresse = $request->query->get("adresse");
        $num = $request->query->get("num");

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
            return new Response("email invalid.");
        }
        $user= new User();
        $user->setFullname($full_name);
        $user->setPsudo($username);
        $user->setEmail($email);
        $user->setPassword($passwordEncoder->encodePassword($user,$password));
        $user->setRoles(['ROLE_USER']);
        $user->setAdresse($adresse);
        $user->setNum($num);
        $user->setCode(0);

        try {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return new JsonResponse("account is created" , 200);
        }catch (\Exception $ex){
            return new Response("exeception".$ex->getMessage());
        }

    }

    /**
     * @Route("/apiuser/signin", name="api_signin")
     */
    public function signin(Request $request)
    {
        $username = $request->query->get("username");
        $password = $request->query->get("password");
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->findOneBy(['psudo'=>$username]);

        if ($user){
            if (password_verify($password,$user->getPassword())){
                $serializer = new Serializer([new ObjectNormalizer()]);
                $formatted = $serializer->normalize($user);
                return new JsonResponse($formatted);
            }
            else {
                return new Response("password not found ");
            }
        }
        else{
            return new Response("user not found");
        }
    }

    /**
     * @Route("/apiuser/edituser", name="api_edituser")
     */
    public function edituser(Request $request  )
    {
      $id = $request->get("id");
        $email = $request->query->get("email");
        $username = $request->query->get("username");
        $name = $request->query->get("full_name");
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->find($id);
        if ($request->files->get("image")!= null){
            $file = $request->files->get("image");
            $fileName = $file->getClientOriginalName();
            $file->move($fileName);
            $user->setImage($fileName);
        }
        $user->setFullname($name);
        $user->setPsudo($username);
        $user->setEmail($email);

        try {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return new JsonResponse("sucess" , 200);
        }catch (\Exception $ex){
            return new Response("failed".$ex->getMessage());
        }
    }

    /**
     * @Route("/apiuser/getpasswordbyemail", name="api_getpasswordbyemail")
     */
    public function getpasswordbyemail(Request $request)
    {

        $email=$request->get('email');
        $user=$this->getDoctrine()->getManager()->getRepository(User::class)->findOneBy(['email'=>$email]);
        if ($user){
            $password=$user->getPassword();
                   $serializer = new Serializer([new ObjectNormalizer()]);
                $formatted = $serializer->normalize($password);
                return new JsonResponse($formatted);
        }
        return new Response("user not found");
    }

    /**
     * @Route("/apiuser/afficher", name="api_afficher")
     */
    public function allusers()
    {

        $x = $this->getDoctrine()->getManager()->getRepository(User::class)->findAll();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($x);

        return new JsonResponse($formatted);

    }


    /**
     * @Route("/ajouterEquipeJSON",name="ajouterEquipeJSON")
     */

    public function ajouterEquipeJSON(Request $request,NormalizerInterface $Normalizer)
    {
        $em = $this->getDoctrine()->getManager();
        $Equipe = new Equipe();
        $Equipe->setNomEquipe($request->get('nom'));
        $Equipe->setTypeEquipe($request->get('type'));
        $Equipe->setDescriptionEquipe($request->get('description'));
        $Equipe->setMailEquipe($request->get('email'));
        $Equipe->setNbreJoueur($request->get('nbre'));
        $em->persist($Equipe);
        $em->flush();
        $jsonContent = $Normalizer->normalize($Equipe, 'json',['groups'=>'post:read']);
        return new Response(json_encode($jsonContent));;
    }
    /**
     * @Route("/Allequipes", name="Allequipes")
     */
    public function getEquipeJSON(NormalizerInterface $Normalizer)
    {


        $Equipe=$this->getDoctrine()->getManager()->getRepository( Equipe::class)->findAll();
        $serializer=new Serializer([new ObjectNormalizer()]);
        $formatted=$serializer->normalize($Equipe);
        return new JsonResponse($formatted);



    }
    /**
     * @Route("/deleteEquipeE", name="deleteEquipeE")
     */
    public function deleteEquipeJSON(Request $request)

    {$id=$request->get("id");
        $em=$this->getDoctrine()->getManager();
        $Equipe=$em->getRepository(Equipe::class)->find($id);
        if($Equipe!=null)
        {$em->remove($Equipe);
            $em->flush();
            $serialize=new Serializer([new ObjectNormalizer()]);
            $formatted=$serialize->normalize("Equipe supprimé avec succés");
            return new JsonResponse($formatted);



        }

        return new JsonResponse("id invalide");
    }
    /**
     * @Route("/updateequipe1", name="updateequipe1")
     */
    public function updateEquipeJSON(Request $request)

    {
        $id=$request->get("id");
        $em=$this->getDoctrine()->getManager();
        $Equipe=$em->getRepository(Equipe::class)->find($id);
        $Equipe->setNomEquipe($request->get('nom'));
        $Equipe->setTypeEquipe($request->get('type'));
        $Equipe->setDescriptionEquipe($request->get('description'));
        $Equipe->setMailEquipe($request->get('email'));
        $Equipe->setNbreJoueur($request->get('nbre'));
        $em->persist($Equipe);
        $em->flush();
        $serialize=new Serializer([new ObjectNormalizer()]);
        $formatted=$serialize->normalize("Equipe modifie avec succés");
        return new JsonResponse($formatted);

    }

    /**
     * @Route("/ajouterterrainJSON",name="ajouterterrainJSON")
     */

    public function ajouterterrainJSON(Request $request,NormalizerInterface $Normalizer)
    {
        $em = $this->getDoctrine()->getManager();
        $terrain = new Terrain();
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


        $terrain=$this->getDoctrine()->getManager()->getRepository( Terrain::class)->findAll();
        $serializer=new Serializer([new ObjectNormalizer()]);
        $formatted=$serializer->normalize($terrain);
        return new JsonResponse($formatted);



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
    /*******************JSON***********************/
    /**
     * @Route("/addEventE",name="addEventE")
     */

    public function ajouter_evenementJSON(Request $request,NormalizerInterface $Normalizer)
    {
        $em = $this->getDoctrine()->getManager();
        $evenement= new Evenement();
        $evenement->setLibelle($request->get('titre'));
        $evenement->setType($request->get('type'));
        $evenement->setDescr($request->get('desc'));
        // $time = new \DateTime('now');
        // $time->format('Y-m-d');
        // $evenement->setDate($time);
        $em->persist($evenement);
        $em->flush();
        $jsonContent = $Normalizer->normalize($evenement, 'json',['groups'=>'post:read']);
        return new JsonResponse($jsonContent);
    }
    /**
     * @Route("/Allevents", name="Allevents")
     */
    public function get_evenementJSON(NormalizerInterface $Normalizer)
    {


        $evenement=$this->getDoctrine()->getManager()->getRepository( Evenement::class)->findAll();
        $serializer=new Serializer([new ObjectNormalizer()]);
        $formatted=$serializer->normalize($evenement);
        return new JsonResponse($formatted);



    }
    /**
     * @Route("/deleteEventE", name="deleteEventE")
     */
    public function delete_evenementJSON(Request $request)

    {$id=$request->get("id_evenement");
        $em=$this->getDoctrine()->getManager();
        $evenement=$em->getRepository(Evenement::class)->find($id);
        if($evenement!=null)
        {$em->remove($evenement);
            $em->flush();
            $serialize=new Serializer([new ObjectNormalizer()]);
            $formatted=$serialize->normalize("evenement supprimé avec succés");
            return new JsonResponse($formatted);



        }

        return new JsonResponse("id invalide");
    }
    /**
     * @Route("/updateEvent1", name="updateEvent1")
     */
    public function update_evenementJSON(Request $request)

    {
        $id=$request->get("id_evenement");
        $em=$this->getDoctrine()->getManager();
        $evenement=$em->getRepository(Evenement::class)->find($id);
        $evenement->setLibelle($request->get('titre'));
        $evenement->setType($request->get('type'));
        $evenement->setDescr($request->get('desc'));
        $em->persist($evenement);
        $em->flush();
        $serialize=new Serializer([new ObjectNormalizer()]);
        $formatted=$serialize->normalize("evenement modifie avec succés");
        return new JsonResponse($formatted);

    }
    /*******************JSON***********************/
    /**
     * @Route("/addTicketE",name="addTicketE")
     */

    public function ajouter_TicketJSON(Request $request,NormalizerInterface $Normalizer)
    {
        $em = $this->getDoctrine()->getManager();
        $Ticket= new Ticket();
        $Ticket->setLibelle($request->get('titre'));
        $Ticket->setType($request->get('type'));
        $Ticket->setPrix($request->get('prix'));
        // $time = new \DateTime('now');
        // $time->format('Y-m-d');
        // $evenement->setDate($time);
        $em->persist($Ticket);
        $em->flush();
        $jsonContent = $Normalizer->normalize($Ticket, 'json',['groups'=>'post:read']);
        return new JsonResponse($jsonContent);
    }
    /**
     * @Route("/AllTicket", name="AllTicket")
     */
    public function get_TicketJSON(NormalizerInterface $Normalizer)
    {


        $Ticket=$this->getDoctrine()->getManager()->getRepository( Ticket::class)->findAll();
        $serializer=new Serializer([new ObjectNormalizer()]);
        $formatted=$serializer->normalize($Ticket);
        return new JsonResponse($formatted);



    }
    /**
     * @Route("/deleteTicketE", name="deleteTicketE")
     */
    public function delete_TicketJSON(Request $request)

    {$id=$request->get("id_Ticket");
        $em=$this->getDoctrine()->getManager();
        $Ticket=$em->getRepository(Ticket::class)->find($id);
        if($Ticket!=null)
        {$em->remove($Ticket);
            $em->flush();
            $serialize=new Serializer([new ObjectNormalizer()]);
            $formatted=$serialize->normalize("Ticket supprimé avec succés");
            return new JsonResponse($formatted);

        }

        return new JsonResponse("id invalide");
    }

    /**
     * @Route("/updateTicket1", name="updateTicket1")
     */
    public function update_TicketJSON(Request $request)

    {
        $id=$request->get("id_Ticket");
        $em=$this->getDoctrine()->getManager();
        $Ticket=$em->getRepository(Ticket::class)->find($id);
        $Ticket->setLibelle($request->get('titre'));
        $Ticket->setType($request->get('type'));
        $Ticket->setPrix($request->get('prix'));
        $em->persist($Ticket);
        $em->flush();
        $serialize=new Serializer([new ObjectNormalizer()]);
        $formatted=$serialize->normalize("Ticket modifie avec succés");
        return new JsonResponse($formatted);

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
        //$Cours->setNbHeure($request->get('nb_heure'));
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
    //////////////////////////////JSOOON//////////////////////////////////////

    /**
     * @Route("/JSON/tournoi/liste", name="affichageTournoi_json")
     */
    public function JSONindex(TournoiRepository $TournoiRepository, SerializerInterface $serializer): Response
    {
        $result = $TournoiRepository->findD();
        $json = $serializer->serialize($result, 'json', ['groups' => 'post:read']);
        return new JsonResponse($json, 200, [], true);
    }

    /**
     * @Route("/JSON/tournoi/ajout", name="ajoutLocalisationjson")
     */
    public function ajoutTournoiJSON(Request $request, SerializerInterface $serilazer, EntityManagerInterface $em)
    {
        $current = new \DateTime();
//        dd($current);
        $em = $this->getDoctrine()->getManager();
        $tournoi = new Tournoi();

        $tournoi->setNomTournoi($request->get('nom_tournoi'));
//        $tournoi->setDateTournoi($request->get('date_tournoi'));
        $tournoi->setDateTournoi($current);
        $tournoi->setResultatTournoi($request->get('resultat_tournoi'));
        $tournoi->setNbParticipants($request->get('nb_participants'));
        $tournoi->setImageTournoi($request->get('image_tournoi'));
        $tournoi->setHeure($request->get('heure'));


        $em->persist($tournoi);
        $em->flush();

        $jsonContent = $serilazer->serialize($tournoi, 'json', ['groups' => "post:read"]);
        return new Response(json_encode($jsonContent));
    }


    /**
     * @Route("/JSON/tournoi/modif", name="modifLocalisationjson")
     */
    public function modifTournoijson(Request $request, SerializerInterface $serilazer)
    {
        $em = $this->getDoctrine()->getManager();
        $current = new \DateTime();
        $tournoi = $em->getRepository(Tournoi::class)->find($request->get('id_tournoi'));
        //  $user = $em->getRepository(User::class)->find($user_id);

        $tournoi->setNomTournoi($request->get('nom_tournoi'));
//        $tournoi->setDateTournoi($request->get('date_tournoi'));
        $tournoi->setDateTournoi($current);
        $tournoi->setResultatTournoi($request->get('resultat_tournoi'));
        $tournoi->setNbParticipants($request->get('nb_participants'));
        $tournoi->setImageTournoi($request->get('image_tournoi'));
        $tournoi->setHeure($request->get('heure'));

        $em->persist($tournoi);
        $em->flush();
        $jsonContent = $serilazer->serialize($tournoi, 'json', ['groups' => "post:read"]);
        return new Response(json_encode($jsonContent));
    }
    /////////////delete JSON///////////////
    /**
     * @Route("/JSON/tournoi/delete/{id}", name="delete_localisationjson")
     */

    public function deletejson(Request $request,$id)
    {


        $em = $this->getDoctrine()->getManager();
        $tournoi = $em->getRepository(Tournoi::class)->find($id);
        if ($tournoi != null) {
            $em->remove($tournoi);
            $em->flush();

            $serialize = new Serializer([new ObjectNormalizer()]);
            $formatted = $serialize->normalize("tournoi supprime avec succes");
            return new JsonResponse($formatted);
        }
        return new JsonResponse("id de tournoi est invalide");
    }
    /*******************JSON***********************/
    /**
     * @Route("/addProduitE",name="addProduitE")
     */

    public function ajouter_ProduitJSON(Request $request,NormalizerInterface $Normalizer)
    {
        $em = $this->getDoctrine()->getManager();
        $Produit= new Produit();
        $Produit->setNomprod($request->get('nom'));
        $Produit->setDescprod($request->get('desc'));
        $Produit->setPrixprod($request->get('prix'));
        $Produit->setQuantprod($request->get('quan'));
        // $time = new \DateTime('now');
        // $time->format('Y-m-d');
        // $evenement->setDate($time);
        $em->persist($Produit);
        $em->flush();
        $jsonContent = $Normalizer->normalize($Produit, 'json',['groups'=>'post:read']);
        return new JsonResponse($jsonContent);
    }
    /**
     * @Route("/AllProduit", name="AllProduit")
     */
    public function get_ProduitJSON(NormalizerInterface $Normalizer)
    {


        $Produit=$this->getDoctrine()->getManager()->getRepository( Produit::class)->findAll();
        $serializer=new Serializer([new ObjectNormalizer()]);
        $formatted=$serializer->normalize($Produit);
        return new JsonResponse($formatted);



    }
    /**
     * @Route("/deleteProduitE", name="deleteProduitE")
     */
    public function delete_ProduitJSON(Request $request)

    {$id=$request->get("id_Produit");
        $em=$this->getDoctrine()->getManager();
        $Produit=$em->getRepository(Produit::class)->find($id);
        if($Produit!=null)
        {$em->remove($Produit);
            $em->flush();
            $serialize=new Serializer([new ObjectNormalizer()]);
            $formatted=$serialize->normalize("Produit supprimé avec succés");
            return new JsonResponse($formatted);



        }

        return new JsonResponse("id invalide");
    }
    /**
     * @Route("/updateProduit1", name="updateProduit1")
     */
    public function update_ProduitJSON(Request $request)

    {
        $id=$request->get("id_Produit");
        $em=$this->getDoctrine()->getManager();
        $Produit=$em->getRepository(Produit::class)->find($id);
        $Produit->setNomprod($request->get('nom'));
        $Produit->setDescprod($request->get('desc'));
        $Produit->setPrixprod($request->get('prix'));
        $Produit->setQuantprod($request->get('quan'));
        $em->persist($Produit);
        $em->flush();
        $serialize=new Serializer([new ObjectNormalizer()]);
        $formatted=$serialize->normalize("Produit modifie avec succés");
        return new JsonResponse($formatted);

    }
}
