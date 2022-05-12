<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RequestResetPasswordType;
use App\Form\ResetPasswordType;
use App\Repository\UserRepository;
use App\Security\LoginformAuthenticator;
use App\Service\TokenGenerator;
use Psr\Log\LoggerInterface;
use Swift_Mailer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Twig\Environment;

class SecurityController extends AbstractController
{
    private $router;
    private $twig;
    /**
     * Mailer constructor.

     * @param RouterInterface $router
     * @param Environment $twig

     */
    public function __construct( RouterInterface $router, Environment $twig)
    {

        $this->router = $router;
        $this->twig = $twig;

    }
    /**
     * @Route("/security", name="app_security")
     */
    public function index(): Response
    {
        return $this->render('security/index.html.twig', [
            'controller_name' => 'SecurityController',
        ]);
    }
    /**
     * @Route("/Back", name="back")
     */
    public function backhome()
    {

        return $this->render('back/admin.html.twig');
    }

    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one

        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user

        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
    /**
     * @Route("/request-password-reset1", name="request-password-reset1")
     * @param Request $request
     * @param TokenGenerator $tokenGenerator
     * @param Swift_Mailer $mailer
     * @return Response
     * @throws \Throwable
     */
    public function forgotPassword1(Request $request, UserRepository $userRepository, Swift_Mailer $mailer, TokenGeneratorInterface $tokenGenerator)
    {
        $form = $this->createForm(RequestResetPasswordType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $donnees = $form->getData();//
            $user = $userRepository->findOneBy(['email' => $donnees]);
            if (!$user) {
                $this->addFlash('danger', 'adresse not found,user not found');
                return $this->redirectToRoute("app_login");


            }
            try {
                $token = $tokenGenerator->generateToken();
                $user->setToken($token);
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();
            }catch(\Exception $exception){
                $this->addFlash('warning', 'adresse found but erreur persist'.$exception->getMessage());
                return $this->redirectToRoute("app_login");
            }

            $url = $this->router->generate('reset_password1', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);


            $message=(new \Swift_Message('mot de pass oublier '))
                ->setFrom("skanderhelmisportify@gmail.com")
                ->setTo($user->getUsername())
                ->setDescription("oublier mot de pass")
                ->setBody(

                  $this->renderView(
                      'email/request-password.html.twig',
                      array(

                          'url' => $url,
                          'name'=>$user->getPsudo(),
                      )
                  ),
            'text/html'

                );

            $mailer->send($message);
            $this->addFlash('message', 'message sent');
            return $this->redirectToRoute("app_login");
        }

        return $this->render('email/request-password-reset.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/reset-password1/{token}", name="reset_password1")
     * @param $request Request
     * @param $user User
     * @param $authenticatorHandler GuardAuthenticatorHandler
     * @param $loginFormAuthenticator LoginFormAuthenticator
     * @param UserPasswordEncoderInterface $encoder
     * @return Response
     */
    public function resetPassword1(Request $request, User $user, GuardAuthenticatorHandler $authenticatorHandler,
                                  LoginFormAuthenticator $loginFormAuthenticator, UserPasswordEncoderInterface $encoder)
    {

        $form = $this->createForm(ResetPasswordType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Users $user */
            $user = $form->getData();
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

        return $this->render('email/password-reset.html.twig', ['f' => $form->createView()]);
    }
}
