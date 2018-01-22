<?php

namespace AppBundle\Controller;


use AppBundle\Events\AccountCreateEvent;
use AppBundle\Events\AccountEvents;
use Doctrine\Common\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\User;
use AppBundle\Form\UserType;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use AppBundle\Entity\UserToken;
use AppBundle\Form\UserTokenType;
use AppBundle\Events\AccountForgotPasswordEvent;
use AppBundle\Form\UserPasswordReinitType;

class AccountController extends Controller
{
    /**
     * @Route("/register", name="account.register")
     */
    public function registerAction(ManagerRegistry $doctrine, Request $request, TranslatorInterface $translator, EventDispatcherInterface $dispatcher):Response
    {
        //creation d'un formulaire
        //il est nécessaire d'instancier la classe User
        $entity = new User();
        $type = UserType::class;
        $form = $this->createForm($type, $entity);
        //handleRequest va récupérer ce que l'on a saisi la première fois, ça permet notamment de re remplir les champs si la soumission n'est pas correcte
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            //récupération de la saisie
            $data = $form->getData(); //est une instance de Entity/User
            $em = $doctrine->getManager();
            $em->persist($data);
            $em->flush();

            //declencher l'événement AccountEvents::CREATE
            $this->addFlash('notice', $translator->trans('flash_message.new_user'));
            //événement
            $event = new AccountCreateEvent();
            $event->setUser($data);
            $event->setUserType("un argument custom");
//
            // déclencher l'événement AccountEvents::CREATE
            $dispatcher->dispatch(AccountEvents::CREATE, $event);

            //redirect
            return $this->redirectToRoute('security.login');
        }


         return $this->render('account/register.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/password-forgot", name="account.password.forgot")
     */
    public function passwordForgotAction(ManagerRegistry $doctrine, Request $request, TranslatorInterface $translator, EventDispatcherInterface $dispatcher):Response
    {
        $entity = new UserToken();
        $type = UserTokenType::class;
        $form = $this->createForm($type, $entity);

        $form->handleRequest($request);
        //si la page est soumise, on exécute les actions suivantes

//        dump($form->getData());

        $token = $form->getData()->setToken(bin2hex(random_bytes(8)));

        if($form->isSubmitted() && $form->isValid()){

            //on renvoie un message de succès ANYWAY
            $this->addFlash('notice', $translator->trans('flash_message.password_forgot.success'));

            //on balance l'evt
            $event = new AccountForgotPasswordEvent();
            $event->setPostData($form->getData());
            $dispatcher->dispatch(AccountEvents::PASSWORD_FORGOT, $event);

            //init token
//            dump($form->getData());
//            die('rr');
//            $token = $form->getData()->getToken();

        }

        return $this->render('account/password.forgot.html.twig', [
            'form' => $form->createView()
//            ,'token' => $token
        ]);
    }

    /**
     * @Route("reinit-password/{token}",name="account.password.reset")
     */

    public function passwordResetAction(
        ManagerRegistry $doctrine,
        Request $request,
        TranslatorInterface $translator,
        EventDispatcherInterface $dispatcher,
        $token
    )
    {
        $entity = new User();
        $type = UserPasswordReinitType::class;
        $form = $this->createForm($type, $entity);
//
//
//        $form->handleRequest($request);
//        //si la page est soumise, on exécute les actions suivantes
//
//        if($form->isSubmitted() && $form->isValid()){
//
//        }

        if($form->isSubmitted() && $form->isValid()){

        }

        return $this->render('account/reinit.password.html.twig', [
            'form' => $form->createView()
        ]);

    }



}
