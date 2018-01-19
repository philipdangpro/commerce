<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use AppBundle\Entity\Product;
use AppBundle\Events\AccountCreateEvent;
use AppBundle\Events\AccountEvents;
use Doctrine\Common\Persistence\ManagerRegistry;
use MongoDB\Driver\Manager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\User;
use AppBundle\Form\UserType;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

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
     * @Route("/password-forgot", name="account.password.forgot"
     */
    public function passwordForgotAction():Response
    {
        return $this->render('account/password.forgot.html.twig', [

        ]);
    }


}
