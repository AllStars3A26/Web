<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Entity\Ticket;
use App\Form\ProduitType;
use App\Repository\ProduitRepository;
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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProduitController extends AbstractController
{
    /**
     * @Route("/produit", name="app_produit")
     */
    public function index(): Response
    {
        return $this->render('produit/index.html.twig', [
            'controller_name' => 'ProduitController',
        ]);
    }
    /**
     * @Route("/add_produit", name="add_p")
     */
    public function add(Request $request)
    {   $prod= new Produit();
        $form= $this->createForm(ProduitType::class,$prod);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($prod);//Add
            $em->flush();

            return $this->redirectToRoute('show_produit');
        }
        return $this->render('produit/createProduit.html.twig',['f'=>$form->createView()]);


    }
    /**
     * @Route("/showback", name="show_produit")
     */
    public function show()
    {
        $prod = $this->getDoctrine()->getManager()->getRepository(Produit::class)->findAll();
        return $this->render('produit/showback.html.twig', [
            'prod' => $prod
        ]);
    }
    /**
     * @param ProduitRepository $repository
     * @return Response
     * @Route("/pdfbox2",name="pdfbox2",methods={"GET"})
     */
    public function pdf(ProduitRepository $repository):Response{
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);
        $Recuperations=$repository->findAll();
        // Retrieve the HTML generated in our twig file
        $html=$this->renderView('produit/printshow.html.twig',[
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
    /**
     * @Route("/showfront", name="show_front")
     */
    public function showfront()
    {
        $prod = $this->getDoctrine()->getManager()->getRepository(Produit::class)->findAll();
        return $this->render('produit/show.html.twig', [
            'prod' => $prod
        ]);
    }
    /**
     * @Route("/remove/{id}", name="supp_prod")
     */
    public function suppression(Produit  $a): Response
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($a);
        $em->flush();

        return $this->redirectToRoute('show_produit');
    }
    /**
     * @Route("/modifier/{id}", name="modifier_prod")
     */
    public function update_ALL(Request $request,$id)
    {   $prod = $this->getDoctrine()->getManager()->getRepository(Produit::class)->find($id);

            $form= $this->createForm(ProduitType::class,$prod);

            $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->flush();
                return $this->redirectToRoute('show_produit');
            }
            return $this->render('produit/ModifierProduit.html.twig',['f'=>$form->createView()]);

    }

    /**
     *
     * @Route("Produit/{id}/qr", name="create_qr_code_Produit")
     */
    public function create_qr_code(Request $request, Produit $Produit)
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

        $label = Label::create($Produit->getNomprod())
            ->setTextColor(new Color(255, 0, 0));
        $result = $writer->write($qrCode,null,$label);
        header('Content-Type: '.$result->getMimeType());
        echo $result->getString();
        $result->saveToFile(__DIR__.'/qrcode'.$Produit->getNomprod().'.png');
        $dataUri = $result->getDataUri();

        return $this->redirectToRoute('');
    }

    /**
     * @Route("qrcode_Produit/{id}", name="qrcode_Produit")

     */
    public function qrcodeAction(Request $request, Produit $Produit) {

        $data = "Nom : " . $Produit->getNomprod() . "; type : " . $Produit->getPrixprod();

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

        return $this->render('produit/QrCode.html.twig', array('qr_code' => $result->getDataUri()));
    }
}
