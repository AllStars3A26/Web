<?php

namespace App\Controller;

require 'C:\Users\Lenovo\Documents\Mel0\Newone\vendor\autoload.php';

use App\services\QrCodeService;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Twilio\Rest\Client;

use App\Entity\User;
use App\Entity\Tournoi;
use App\Entity\ParticipantsTournoi;
use App\Repository\ReclamationRepository;
use App\Repository\TournoiRepository;
use App\Repository\PTournoiRepository;
use App\Form\TournoiType;
use Swift_Attachment;
use Swift_Image;
use Swift_Message;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
// Include PhpSpreadsheet required namespaces
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\JsonResponse;
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
     * @Route("/tournoi/listef", name="afficher_tournoif")
     */
    public function afficherf(Request $request,PaginatorInterface $paginator): Response
    {
        $tournois = $this->getDoctrine()
            ->getRepository(Tournoi::class)
            ->findAll();
        $data=$paginator->paginate(
            $tournois,
            $request->query->getInt('page',1),
            4
        );
        return $this->render('frontTournoibase.html.twig', array(
            //data
            'tournois' => $data
        ));
    }

    /**
     * @Route("/tournoi/listeAdd", name="ajouter_tournoi")
     */
    public function ajouter(Request $request,MailerInterface $mailer, EntityManagerInterface $entityManager): Response
    {
        $tournoi = new Tournoi();

        $form = $this->createForm(TournoiType::class, $tournoi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('imageTournoi')->getData();
            $filename = md5(uniqid()).'.'.$file->guessExtension();
            $file->move($this->getParameter('uploads_directory'),$filename);
            $tournoi->setImageTournoi($filename);
            $userNum='+21650745839';
            $tournoi = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($tournoi);
            $em->flush();
            self::twilio($entityManager,$userNum);
            //self::mailrec( $mailer, $entityManager,$qrcodeService,$tournoi->getNomTournoi(),$tournoi->getResultatTournoi(),$tournoi->getNbParticipants());
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
    /**
     * @Route("/qr/{id}", name="qr")
     */
    public function qr($id,Request $request, QrCodeService $qrcodeService,PaginatorInterface $paginator): Response
    {
        $qrCode = null;
        $tournoi = $this->getDoctrine()
            ->getRepository(Tournoi::class)
            ->find($id);
        $url = 'nom: '.$tournoi->getNomTournoi().' | ';
        $url = $url.'Resultat tournoi: '.$tournoi->getResultatTournoi().' | ';
        $url = $url.'Nb participants: '.$tournoi->getNbParticipants();

        $qrCode = $qrcodeService->qrcode($url);

       $tournois = $this->getDoctrine()->getRepository(Tournoi::class)->findAll();



        return $this->render('tournoi/qr.html.twig', [
            'tournois' => $tournois,
            'qrCode' => $qrCode
        ]);

    }
    /**
     * @Route("/api/excel", name="excel")
     */
    public function excel()
    {
        $spreadsheet = new Spreadsheet();
        $tournois = $this->getDoctrine()
            ->getRepository(ParticipantsTournoi::class)
            ->findAll();
        /* @var $sheet \PhpOffice\PhpSpreadsheet\Writer\Xlsx\Worksheet */
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', "Tournois");
        $sheet->setCellValue('B1', "Equipes");
        $i=2;
        foreach ($tournois as $tournoi)
        {
            $sheet->setCellValue('A' . $i, $tournoi->getIdTournoi());
            $sheet->setCellValue('B' . $i, $tournoi->getIdEquipe());

            $i++;


        }


        $sheet->setTitle("Participants Tournois");

        // Create your Office 2007 Excel (XLSX Format)
        $writer = new Xlsx($spreadsheet);

        // Create a Temporary file in the system
        $fileName = 'participants_tournois.xlsx';
        $temp_file = tempnam(sys_get_temp_dir(), $fileName);

        // Create the excel file in the tmp directory of the system
        $writer->save($temp_file);

        // Return the excel file as an attachment
        return $this->file($temp_file, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);
    }

    /**
     * @Route("/api/excel1/{id}", name="excel1")
     */
    public function excel1($id,PTournoiRepository $rep)
    {
        $spreadsheet = new Spreadsheet();
        $tournois = $rep->findByIdP($id);
        $em = $this->getDoctrine()->getManager();




        /* @var $sheet \PhpOffice\PhpSpreadsheet\Writer\Xlsx\Worksheet */
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', "Equipes");

        $i=2;
        foreach ($tournois as $tournoi)
        {
            $sheet->setCellValue('A' . $i, $tournoi->getIdEquipe());


            $i++;


        }
        $nomm = $this->getDoctrine()
            ->getRepository(Tournoi::class)
            ->find($id);
        $nom = "Participants du tournoi ".$nomm->getNomTournoi();
        $sheet->setTitle($nom);

        // Create your Office 2007 Excel (XLSX Format)
        $writer = new Xlsx($spreadsheet);

        // Create a Temporary file in the system
        $fileName = $nom.'.xlsx';
        $temp_file = tempnam(sys_get_temp_dir(), $fileName);

        // Create the excel file in the tmp directory of the system
        $writer->save($temp_file);

        // Return the excel file as an attachment
        return $this->file($temp_file, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);
    }
    /**
     * @Route("/s/searchT", name="searchT")
     */
    public function searchArticles(Request $request,SerializerInterface $serializer,TournoiRepository $repository):Response
    {
//$repository = $this->getDoctrine()->getRepository(Article::class);
        $encoders = [new JsonEncoder()]; // If no need for XmlEncoder
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);

        $requestString = $request->get('searchValue');
        $tournois = $repository->findByNom($requestString);
        $jsonContent = $serializer->serialize($tournois, 'json', [
            'Tournois' => function ($object) {
                return $object->getIdTournoi();
            }]);

        return new Response($jsonContent);
    }
    public function mailrec( MailerInterface $mailer,EntityManagerInterface $entityManager, QrCodeService $qrcodeService,$nom,$resultat,$nb)
    {
        $qrCode = null;

        $url = 'nom: '.$nom.' | ';
        $url = $url.'Resultat tournoi: '.$resultat.' | ';
        $url = $url.'Nb participants: '.$nb;

        $qrCode = $qrcodeService->nomqr($url);


        $fan=new User();


        $message = (new TemplatedEmail())
            ->from('sportifyapplication2022@gmail.com');

      //  $message->setStreamOptions(array('ssl' => array('allow_self_signed' => true, 'verify_peer' => false)));




        $managers = $entityManager
            ->getRepository(User::class)
            ->findAll();
        $tournois = $entityManager
            ->getRepository(Tournoi::class)
            ->findAll();
        foreach($managers as $manager){
            $name=$fan->getNom()." ".$fan->getPrenom();

            $message->to($manager->getEmail())
                ->htmlTemplate('tournoi/email.html.twig')
                ->context(['name' => $name,
                    'qrCode' => $qrCode
                ]);

            $mailer->send ( $message );
        }
        $this->addFlash('message','mail envoye');
        return $this->render('tournoi/index.html.twig', [
            'tournois' => $tournois,
        ]);
    }
    /**
     * @Route("/send", name="twilio")
     */
    public function twilio(EntityManagerInterface $entityManager,$userNum)
    {$tournois = $entityManager
        ->getRepository(Tournoi::class)
        ->findAll();
        $sid='AC6cba527d728e15afa4914a88f33e1800';
        $token='ef4d8471fb53b323f88be4863caeba73';
$client = new Client($sid,$token);
$client->messages->create(
    $userNum,array(
    'from'=>'+16626678504',
    'body'=>'nouveau tournoi ajouté ! '
    )
);
        return $this->render('tournoi/index.html.twig', [
            'tournois' => $tournois,
        ]);
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
}
