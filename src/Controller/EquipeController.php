<?php

namespace App\Controller;
use App\Entity\Equipe;
use App\Form\EquipeType;
use App\Repository\EquipeRepository;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Knp\Component\Pager\PaginatorInterface;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelLow;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use CMEN\GoogleChartsBundle\GoogleCharts\Options\PieChart\PieSlice;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;
use Knp\Bundle\PaginatorBundle\KnpPaginatorBundle;

use Dompdf\Dompdf;
use Dompdf\Options;


use MercurySeries\FlashyBundle\FlashyNotifier;
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
    public function listFonction(Request $request,PaginatorInterface $paginator)
    {
        $Equipe = $this->getDoctrine()->getRepository(Equipe::class)->findAll();

        $Equipe=$paginator->paginate(
            $Equipe,
            $request->query->getInt('page',1),
            6

        );

      
        return $this->render('equipe/list.html.twig', ["Equipe" => $Equipe]);
    }
/**
     * @Route("/ajouterEquipe", name="ajouterEquipe")
     * @param \Swift_Mailer $mailer
     */
    public function ajouterEquipe(Request $request,\Swift_Mailer $mailer )
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
            $message = (new \Swift_Message( 'You Got Mail!'))
                ->setFrom('tennisclubtunis1@gmail.com')

                ->setTo($Equipe->getMailEquipe())
                ->setBody(

                    'votre équipe: '.$Equipe->getNomEquipe().'a été ajouté avec succée'
                )
            ;
            $mailer->send($message);
            $this->addFlash('success', 'It sent!');

            //$messageBird = new \MessageBird\Client('TxU2kuOHjwhBbsbvLpHOUDIfj'); //test
            $messageBird = new \MessageBird\Client('QYakTpmICHeRsHuQvO8EZnEpB'); //live
            $message =  new \MessageBird\Objects\Message();
            try{

                $message->originator = '+21692892789';
                $message->recipients = '+21692892789';
                $message->body = 'l équipe:'.$Equipe->getNomEquipe().' est bien confirmé';
                $response = $messageBird->messages->create($message);


                print_r($response);
            }
            catch(Exception $e) {echo $e;}



            
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
    public function modifierEquipe(Request $request, $id,FlashyNotifier $flashy)
    {
        $Equipe = $this->getDoctrine()->getRepository(Equipe::class)->find($id);
        $form = $this->createForm(EquipeType::class, $Equipe);
        $form->add("Modifier", SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted()&& $form->isValid())  {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $flashy->success('Equipe Modifier avec Succee !');
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


    /**
     *
     * @Route("equipe/{id}/qr", name="create_qr_code_equipe")
     */
    public function create_qr_code(Request $request, Equipe $Equipe)
    {
        $writer = new PngWriter();
        $qrCode = QrCode::create('1')
            ->setEncoding(new Encoding('UTF-8'))
            ->setErrorCorrectionLevel(new ErrorCorrectionLevelLow())
            ->setSize(300)
            ->setMargin(10)
            ->setRoundBlockSizeMode(new RoundBlockSizeModeMargin())
            ->setForegroundColor(new Color(0, 0, 0))
            ->setBackgroundColor(new Color(255, 255, 255));

        $label = Label::create($Equipe->getNomEquipe())
            ->setTextColor(new Color(255, 0, 0));
        $result = $writer->write($qrCode,null,$label);
        header('Content-Type: '.$result->getMimeType());
        echo $result->getString();
        $result->saveToFile(__DIR__.'/qrcode'.$Equipe->getNomEquipe().'.png');
        $dataUri = $result->getDataUri();

        return $this->redirectToRoute('');
    }

    /**
     * @Route("qrcode_equipe/{id}", name="qrcode_equipe")

     */
    public function qrcodeAction(Request $request, Equipe $Equipe) {

        $data = "Nom : " . $Equipe->getNomEquipe() . "; type : " . $Equipe->getTypeEquipe()
            . "; nbre_joueur : " . $Equipe->getNbreJoueur();

        $writer = new PngWriter();
        $qrCode = QrCode::create($data)
            ->setEncoding(new Encoding('UTF-8'))
            ->setErrorCorrectionLevel(new ErrorCorrectionLevelLow())
            ->setSize(300)
            ->setMargin(10)
            ->setRoundBlockSizeMode(new RoundBlockSizeModeMargin())
            ->setForegroundColor(new Color(0, 0, 0))
            ->setBackgroundColor(new Color(255, 255, 255));

        $result = $writer->write($qrCode,null,null);
        header('Content-Type: '.$result->getMimeType());
        //$result->saveToFile(__DIR__.'/qrcode'.$restaurent->getNom().'.png');
        $dataUri = $result->getDataUri();

        return $this->render('equipe/QrCode.html.twig', array('qr_code' => $result->getDataUri()));
    }


    /**
     * @Route("/statss", name="statss")
     */
    public function stats(EquipeRepository $repo)
    {
        $rem = $repo->statistics('football');
        $cad = $repo->statistics('basketball');
        $total  = $rem + $cad;
        $pieChart = new PieChart();
        $pieChart->getData()->setArrayToDataTable(
            [
                ['Type', 'Pourcentage'],
                ['football', ($rem * 100) / $total],
                ['basketball', ($cad * 100) / $total]
            ]
        );
        $pieChart->getOptions()->setTitle("Type d'équipe");
        $pieChart->getOptions()->getTitleTextStyle()->setItalic(true);
        $pieChart->getOptions()->getTitleTextStyle()->setFontName('Arial');
        $pieChart->getOptions()->setIs3D(true);

        $pieChart->getOptions()->getLegend()->setPosition('down');
        $pieChart->getOptions()->setPieSliceText('');
        $pieChart->getOptions()->setPieStartAngle(0);

        $pieSlice1 = new PieSlice();
        $pieSlice1->setColor('blue');
        $pieSlice2 = new PieSlice();
        $pieSlice2->setColor('green');
        $pieChart->getOptions()->setSlices([$pieSlice1, $pieSlice2]);

        $pieChart->getOptions()->setHeight(500);
        $pieChart->getOptions()->setWidth(900);
        $pieChart->getOptions()->getTooltip()->setTrigger('none');

        return $this->render('Equipe/stat.html.twig', array('piechart' => $pieChart));
    }


    /**
     * @Route("/Pdf", name="Pdf")
    */
    public function GeneratePdf()
    {

        $repository = $this->getDoctrine()->getRepository(Equipe::class);
        $Equipe = $repository->findAll();

        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);

        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('Equipe/mypdf.html.twig', [
            'Equipe' => $Equipe,
        ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (inline view)
        $dompdf->stream("mypdf.pdf", [
            //"Attachment" => false
        ]);
    }

    /**
     * @param $type
     * @param EquipeRepository $repo
     * @Route ("/searchEquipe", name="searchEquipe")
     * @return Response
     */
    public function searchEquipe(EquipeRepository $repo, Request $request)
    {
        $data = $request->get('search');
        $Equipe = $repo->findBy(['title' => $data]);

        if($Equipe == null){
            return $this->render("Equipe/list.html.twig",[
                "Equipe" => $Equipe,"found" => false
            ]);
        }
        return $this->render("Equipe/list.html.twig",[
           "Equipe" => $Equipe,"found" => true
        ]);
    }



    /**
     * @param EquipeRepository $repo
     * @param Request $request
     * @return Response
     * @Route("/triIDC", name="triIDC")
     */
    public function triIDC(EquipeRepository $repo, Request $request)
    {
        $Equipe = $repo->triByIdCroissant();
        return $this->render("Equipe/listfront.html.twig",[
            "Equipe" => $Equipe, 'found' => true
        ]);

    }

    /**
     * @param EquipeRepository $repo
     * @param Request $request
     * @return Response
     * @Route("/triIDD", name="triIDD")
     */
    public function triIDD(EquipeRepository  $repo, Request $request)
    {
        $Equipe = $repo->triByIdDecroissant();
        return $this->render("Equipe/listfront.html.twig",[
            "Equipe" => $Equipe, 'found' => true
        ]);
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
       
       $Equipe = $em->getRepository(Equipe::class)->findEntitiesByString($requestString);
       
       
       if (!$Equipe) {
           $result['Equipe']['error'] = "product introuvable 🙁 ";
       } else {
           $result['Equipe'] = $this->getRealEntities($Equipe);
       }
       
       return new Response(json_encode($result));
       
   }
 
   public function getRealEntities($Equipe){
 
     foreach ($Equipe as $Equipe){
         $realEntities[$Equipe->getId()] = [$Equipe->getNomEquipe() ,$Equipe->getTypeEquipe(),$Equipe->getDescriptionEquipe(),$Equipe->getMailEquipe()];
     }
 
     return $realEntities;
 }
 
 /**
      * @Route("/detailEquipe/{id}", name="detailEquipe")
      */
     public function DetailEquipe($id)
     {
         $Equipe = $this->getDoctrine()->getRepository(Equipe::class)->find($id);
 
         return $this->render('Equipe/detail.html.twig', ["Equipe" => $Equipe]);
     }



     /*******************JSON***********************/
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

    
}
