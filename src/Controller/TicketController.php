<?php

namespace App\Controller;
use App\Entity\Ticket;
use App\Entity\User;
use App\Form\TicketType;
use App\Repository\TicketRepository;


use Dompdf\Dompdf;
use Dompdf\Options;
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
    public function listFonction()
    {
        $ticket = $this->getDoctrine()->getRepository(Ticket::class)->findAll();
      
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
}
