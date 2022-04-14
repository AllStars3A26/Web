<?php

namespace App\Controller;

use App\Form\AdherentType;
use App\Form\EntraineurType;
use App\Form\UserType;
use App\Form\UtilisateurType;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\User;

class UserController extends AbstractController
{
    /**
     * @Route("/user", name="display_user")
     */
    public function showAll()
    {
        $user = $this->getDoctrine()->getManager()->getRepository(User::class)->findAll();
        return $this->render('user/show.html.twig', [
            'user' => $user
        ]);
    }
    /**
     * @Route("/front_user", name="front_user")
     */
    public function index():Response
    {
        return $this->render('Adherent/index.html.twig');
    }
    /**
     * @Route("/RegisterAdmin", name="register_Admin")
     */
    public function register_Admin(Request $request,UserPasswordEncoderInterface $passwordEncoder)
    {   $User = new User();
        $form= $this->createForm(UserType::class,$User);
        $password = $passwordEncoder->encodePassword($User, $User->getPlainPassword());
        $User->setPassword($password);
        $form->handleRequest($request);
        $User->setRole(0);
        if($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($User);//Add
            $em->flush();

            return $this->redirectToRoute('display_user');
        }
        return $this->render('user/Register_Utilisateur.html.twig',['f'=>$form->createView()]);


    }
    /**
     * @Route("/RegisterAdherent", name="register_Adherent")
     */
    public function register_Adherent(Request $request,UserPasswordEncoderInterface $passwordEncoder)
    {   $User = new User();
        $form= $this->createForm(AdherentType::class,$User);
        $password = $passwordEncoder->encodePassword($User, $User->getPlainPassword());
        $User->setPassword($password);
        $form->handleRequest($request);
        $User->setRole(1);
        $time = new \DateTime('now');
        $time->format('Y-m-d');
        $User->setDateInscritU($time);
        if($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($User);//Add
            $em->flush();

            return $this->redirectToRoute('display_user');
        }
        return $this->render('Adherent/Register_Adh.html.twig',['f'=>$form->createView()]);


    }

    /**
     * @Route("/removeuser/{id}", name="supp_user")
     */
    public function suppression(User  $a): Response
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($a);
        $em->flush();

        return $this->redirectToRoute('display_user');
    }
    /**
     * @Route("/modifier/{id}", name="modifier_user")
     */
    public function update_ALL(Request $request,UserPasswordEncoderInterface $passwordEncoder,$id)
    {   $User = $this->getDoctrine()->getManager()->getRepository(User::class)->find($id);
        if ($User->getRole() == 0) {
            $form= $this->createForm(UserType::class,$User);
            $password = $passwordEncoder->encodePassword($User, $User->getPlainPassword());
            $User->setPassword($password);
            $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->flush();
                return $this->redirectToRoute('display_user');
            }
            return $this->render('user/Modifier_Utilisateur.html.twig',['f'=>$form->createView()]);
        }
        elseif ($User->getRole() == 1){
            $form= $this->createForm(AdherentType::class,$User);
            $password = $passwordEncoder->encodePassword($User, $User->getPlainPassword());
            $User->setPassword($password);
            $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->flush();

                return $this->redirectToRoute('display_user');
            }
            return $this->render('Adherent/Modifier_Adh.html.twig',['f'=>$form->createView()]);
        }
        else {
            $form= $this->createForm(EntraineurType::class,$User);
            $password = $passwordEncoder->encodePassword($User, $User->getPlainPassword());
            $User->setPassword($password);
            $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->flush();

                return $this->redirectToRoute('display_user');
            }
            return $this->render('Entraineur/Modifier_Entraineur.html.twig',['f'=>$form->createView()]);
        }


        return $this->render('user/Modifier_Utilisateur.html.twig',['f'=>$form->createView()]);

    }
    /**
     * @Route("/RegisterEnt", name="register_Ent")
     */
    public function register_Entraineur(Request $request,UserPasswordEncoderInterface $passwordEncoder)
    {   $User = new User();
        $form= $this->createForm(EntraineurType::class,$User);
        $password = $passwordEncoder->encodePassword($User, $User->getPlainPassword());
        $User->setPassword($password);
        $form->handleRequest($request);
        $User->setRole(2);
        if($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($User);//Add
            $em->flush();

            return $this->redirectToRoute('display_user');
        }
        return $this->render('Entraineur/Register_EN.html.twig',['f'=>$form->createView()]);


    }

    }
