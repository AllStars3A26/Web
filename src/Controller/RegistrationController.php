<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AdminType;
use App\Form\EntraineurType;
use App\Form\ModifierAdheType;
use App\Form\SearchCompagnieType;
use App\Repository\UserRepository;
use App\Form\AdheType;
use App\Form\ResetPasswordType;
use App\Security\LoginFormAuthenticator;
use App\Service\TokenGenerator;
use App\Form\RequestResetPasswordType;
use App\Service\Mailer;
use Swift_Mailer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Exception\ValidatorException;
use Symfony\Contracts\Translation\TranslatorInterface;
use Dompdf\Dompdf;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Dompdf\Options;
use Doctrine\ORM\EntityManagerInterface;
use MercurySeries\FlashyBundle\FlashyNotifier;
class RegistrationController extends AbstractController
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @Route("/aaaaaaaa", name="")
     */
    public function showAll()
    {
        $user = $this->getDoctrine()->getManager()->getRepository(User::class)->findAll();
        return $this->render('back/show.html.twig', [
            'user' => $user
        ]);
    }

    /**
     * @Route("/Front", name="first_page")
     */
    public function first_page()
    {

        return $this->render('front/index.html.twig');
    }

    /**
     * @Route("/home", name="home")
     */
    public function home()
    {

        return $this->render('front/client.html.twig');
    }

    /**
     * @Route("/users_list", name="display_user")
     */
    public function index(Request $request, UserRepository $avRepository, EntityManagerInterface $entityManager)
    {
        $avions = $entityManager->getRepository(User::class)->findAll();
        $form = $this->createForm(SearchCompagnieType::class);

        $search = $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // On recherche les compagnies correspondant aux mots clés
            $avions = $avRepository->search(
                $search->get('mots')->getData()

            );
        }

        return $this->render('back/show.html.twig', [
            'user' => $avions,
            'form' => $form->createView()]);
    }

    /**
     * @Route("/sign_in", name="sign_in")
     */
    public function register_Utilisateur(Request $request,FlashyNotifier $flashy)
    {
        $user = new User();

        $form = $this->createForm(AdminType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Encode the new users password
            $user->setPassword($this->passwordEncoder->encodePassword($user, $user->getPassword()));

            // Set their role
            $user->setRoles(['ROLE_USER']);
            $user->setCode("0");
            $time = new \DateTime('now');
            $time->format('Y-m-d');
            $user->setDateInscritU($time);
            // Save
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            $flashy->success('vous avez ajouter un compte !');
            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/Register_Utilisateur.html.twig', ['f' => $form->createView()]);
    }

    /**
     * @Route("/removeuser/{id}", name="supp_user")
     */
    public function suppression(User $a,FlashyNotifier $flashy): Response
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($a);
        $em->flush();
        $flashy->success('supprimer  !');
        return $this->redirectToRoute('display_user');
    }

    /**
     * @Route("/modifier/{id}", name="modifier_user")
     */
    public function update_ALL(Request $request, $id)
    {
        $User = $this->getDoctrine()->getManager()->getRepository(User::class)->find($id);
        if (in_array('ROLE_ADMIN', $User->getRoles(), true) or in_array('ROLE_SUPER_ADMIN', $User->getRoles(), true)) {
            $form = $this->createForm(AdminType::class, $User);
            $User->setPassword($this->passwordEncoder->encodePassword($User, $User->getPassword()));
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->flush();
                return $this->redirectToRoute('display_user');
            }
            return $this->render('back/modi/adminsetting.html.twig', ['f' => $form->createView()]);
        } elseif (in_array('ROLE_USER', $User->getRoles(), true)) {
            $form = $this->createForm(ModifierAdheType::class, $User);
            $User->setPassword($this->passwordEncoder->encodePassword($User, $User->getPassword()));
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->flush();

                return $this->redirectToRoute('home');
            }
            return $this->render('registration/Modifier_Utilisateur.html.twig', ['f' => $form->createView()]);
        } else {
            $form = $this->createForm(EntraineurType::class, $User);
            $User->setPassword($this->passwordEncoder->encodePassword($User, $User->getPassword()));
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->flush();

                return $this->redirectToRoute('display_user');
            }
            return $this->render('registration/Modifier_Entraineur.html.twig', ['f' => $form->createView()]);
        }


        return $this->render('registration/Modifier_Utilisateur.html.twig', ['f' => $form->createView()]);

    }
    /**
     * @Route("/modifierpwd/{id}", name="modifier_pwd")
     */
    public function updatepassword(Request $request, $id)
    {
        $User = $this->getDoctrine()->getManager()->getRepository(User::class)->find($id);
            $form = $this->createForm(ResetPasswordType::class, $User);


            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $User->setPassword($this->passwordEncoder->encodePassword($User, $User->getPassword()));
                $em = $this->getDoctrine()->getManager();
                $em->flush();
                return $this->redirectToRoute('display_user');
            }
            return $this->render('back/modi/adminmdpsetting.html.twig', ['f' => $form->createView()]);


    }
    /**
     * @Route("/request-password-reset", name="request-password-reset")
     * @param Request $request
     * @param TokenGenerator $tokenGenerator
     * @param Mailer $mailer
     * @param TranslatorInterface $translator
     * @return Response
     * @throws \Throwable
     */
    public function requestPasswordReset(Request $request, TokenGenerator $tokenGenerator, Mailer $mailer,
                                         TranslatorInterface $translator)
    {

        $form = $this->createForm(RequestResetPasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            try {

                $repository = $this->getDoctrine()->getRepository(User::class);

                /** @var Users $user */
                $user = $repository->findOneBy(['email' => $form->get('email')->getData()]);
                if (!$user) {
                    $this->addFlash('warning', 'user not-found');
                    return $this->render('email/request-password-reset.html.twig', [
                        'form' => $form->createView(),
                    ]);
                }

                $token = $tokenGenerator->generateToken();
                $user->setToken($token);
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();

                $mailer->sendResetPasswordEmailMessage($user);

                $this->addFlash('success', 'Password link sent successfully');
                return $this->redirect($this->generateUrl('app_login'));
            } catch (ValidatorException $exception) {

            }
        }

        return $this->render('email/request-password-reset.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/reset-password/{token}", name="reset_password")
     * @param $request Request
     * @param $user User
     * @param $authenticatorHandler GuardAuthenticatorHandler
     * @param $loginFormAuthenticator LoginFormAuthenticator
     * @param UserPasswordEncoderInterface $encoder
     * @return Response
     */
    /*public function resetPassword(Request $request, User $user, GuardAuthenticatorHandler $authenticatorHandler,
                                  LoginFormAuthenticator $loginFormAuthenticator, UserPasswordEncoderInterface $encoder)
    {

        $form = $this->createForm(ResetPasswordType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Users $user */
            /*$user = $form->getData();
            $user->setPassword($encoder->encodePassword($user, $user->getPassword()));
            $user->setToken(null);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'User updated successfully');

            // automatic login
            return $authenticatorHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $loginFormAuthenticator,
                'main'
            );
        }

        return $this->render('email/password-reset.html.twig', ['form' => $form->createView()]);
    }*/

    /**
     * @Route("/activate/{token}", name="activate")
     * @param $request Request
     * @param $user User
     * @param GuardAuthenticatorHandler $authenticatorHandler
     * @param LoginFormAuthenticator $loginFormAuthenticator
     * @return Response
     * @throws \Exception
     */
    public function activate(Request $request, User $user, GuardAuthenticatorHandler $authenticatorHandler, LoginFormAuthenticator $loginFormAuthenticator)
    {
        $user->setCode(true);
        $user->setToken(null);
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        $this->addFlash('success', 'Welcome');

        // automatic login
        return $authenticatorHandler->authenticateUserAndHandleSuccess(
            $user,
            $request,
            $loginFormAuthenticator,
            'main'
        );
    }

    /**
     * @param UserRepository $repository
     * @return Response
     * @Route("/pdfbox",name="pdfbox",methods={"GET"})
     */
    public function pdf(UserRepository $repository): Response
    {
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);
        $Recuperations = $repository->findAll();
        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('back/printshow.html.twig', [
            'Recuperations' => $Recuperations
        ]);

        // Load HTML to DompdfPDF
        $dompdf->loadHtml($html);
// (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (force download)
        $dompdf->stream("userCv.pdf", [
            "Attachment" => true
        ]);

    }
    //search

    /**
     * @param Request $request
     * @param NormalizerInterface $normalizer
     * @return JsonResponse
     * @throws ExceptionInterface
     * @Route("/search",name="search")
     */

    public function search(Request $request, NormalizerInterface $normalizer)
    {
        $repository = $this->getDoctrine()->getRepository(User::class);
        $requestString = $request->get('searchValue');
        $Recuperation = $repository->findBynomrec($requestString);
        $jsonContent = $normalizer->normalize($Recuperation, 'json', ['name' => 'Service:read']);
        $re = json_encode($jsonContent);

        return new Response($re);

    }

    /**
     * @Route("/RegisterEnt", name="register_Ent")
     */
    public function register_Entraineur(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $User = new User();
        $form = $this->createForm(EntraineurType::class, $User);
        $password = $passwordEncoder->encodePassword($User, $User->getPlainPassword());
        $User->setPassword($password);
        $form->handleRequest($request);
        $User->setRole(2);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($User);//Add
            $em->flush();

            return $this->redirectToRoute('display_user');
        }
        return $this->render('Entraineur/Register_EN.html.twig', ['f' => $form->createView()]);


    }

}
