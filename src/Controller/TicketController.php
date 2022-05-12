<?php

namespace App\Controller;
use App\Entity\Equipe;
use App\Entity\Ticket;
use App\Entity\User;
use App\Form\TicketType;
use App\Repository\TicketRepository;


use Dompdf\Dompdf;
use Dompdf\Options;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelLow;
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\Writer\PngWriter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Knp\Component\Pager\PaginatorInterface;



class TicketController extends AbstractController
{
    /**
     * @Route("/ticket", name="ticket")
     */
    public function index(): Response
    {
        return $this->render('Ticket/index.html.twig', [
            'controller_name' => 'TicketController',
        ]);
    }
    /**
     * @Route("/ticketfront", name="ticket")
     */
    public function ind(): Response
    {
        $ticket = $this->getDoctrine()->getRepository(Ticket::class)->findAll();

        return $this->render('Ticket/listfront.html.twig',
            ["Ticket" => $ticket]
        );
    }
      /**
     * @Route("/listFonctionback", name="listFonction1")
     */
    public function listFonction(Request $request,PaginatorInterface $paginator)
    {
        $ticket = $this->getDoctrine()->getRepository(Ticket::class)->findAll();
        $ticket=$paginator->paginate(
            $ticket,
            $request->query->getInt('page',1),
            4);
        return $this->render('Ticket/list.html.twig', ["Ticket" => $ticket]);
    }
/**
     * @Route("/ajouterTicket", name="ajouterTicket")
     */
    public function ajouterTicket(Request $request)
    {
        $Ticket = new Ticket();
        $form = $this->createForm(TicketType::class, $Ticket);
        $form->add("Ajouter", SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            
            $em->persist($Ticket);
            $em->flush();
            return $this->redirectToRoute('listFonction1');
        }
        return $this->render("Ticket/ajouter.html.twig", array('form' => $form->createView()));
    }
     /**
     * @Route("/supprimerTicket/{id}", name="supprimerTicket")
     */
    public function supprimerTicket($id)
    {
        $fonction = $this->getDoctrine()->getRepository(Ticket::class)->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($fonction);
        $em->flush();
        return $this->redirectToRoute("listFonction1");
    } 

      /**
     * @Route("/modifierTicket/{id}", name="modifierTicket")
     */
    public function modifierTicket(Request $request, $id)
    {
        $Ticket = $this->getDoctrine()->getRepository(Ticket::class)->find($id);
        $form = $this->createForm(TicketType::class, $Ticket);
        $form->add("Modifier", SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted()&& $form->isValid())  {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('listFonction1');
        }
        return $this->render("Ticket/modifier.html.twig", array('form' => $form->createView()));
    }
    /**
     * @param TicketRepository $repository
     * @return Response
     * @Route("/pdfbox",name="pdfbox",methods={"GET"})
     */
    public function pdf(TicketRepository $repository):Response{
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);
        $Recuperations=$repository->findAll();
        // Retrieve the HTML generated in our twig file
        $html=$this->renderView('ticket/printshow.html.twig',[
            'Recuperations'=>$Recuperations
        ]);

        // Load HTML to DompdfPDF
        $dompdf->loadHtml($html);
// (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (force download)
        $dompdf->stream("boxpdf.pdf", [
            "Attachment" => true
        ]);

    }
    //search
    /**
     * @param Request $request
     * @param NormalizerInterface $normalizer
     * @return JsonResponse
     * @throws ExceptionInterface|\Symfony\Component\Serializer\Exception\ExceptionInterface
     * @Route("/search",name="search")
     */

    public function search(Request $request,NormalizerInterface $normalizer){
        $repository = $this->getDoctrine()->getRepository(Ticket::class);
        $requestString = $request->get('searchValue');
        $Recuperation = $repository->findBynomrec($requestString);
        $jsonContent = $normalizer->normalize($Recuperation, 'json', ['name' => 'Service:read']);
        $re = json_encode($jsonContent);

        return new Response($re);

    }

    /**
     *
     * @Route("Ticket/{id}/qr", name="create_qr_code_Ticket")
     */
    public function create_qr_code(Request $request, Ticket $Ticket)
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

        $label = Label::create($Ticket->getLibelle())
            ->setTextColor(new Color(255, 0, 0));
        $result = $writer->write($qrCode,null,$label);
        header('Content-Type: '.$result->getMimeType());
        echo $result->getString();
        $result->saveToFile(__DIR__.'/qrcode'.$Ticket->getLibelle().'.png');
        $dataUri = $result->getDataUri();

        return $this->redirectToRoute('');
    }

    /**
     * @Route("qrcode_Ticket/{id}", name="qrcode_Ticket")

     */
    public function qrcodeAction(Request $request, Ticket $Ticket) {

        $data = "Nom : " . $Ticket->getLibelle() . "; type : " . $Ticket->getType();

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

        return $this->render('Ticket/QrCode.html.twig', array('qr_code' => $result->getDataUri()));
    }
}
