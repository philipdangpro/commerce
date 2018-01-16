<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use AppBundle\Entity\Product;
use Doctrine\Common\Persistence\ManagerRegistry;
use MongoDB\Driver\Manager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\User;
use AppBundle\Form\UserType;

class AccountController extends Controller
{
    /**
     * @Route("/register", name="account.register")
     */
    public function registerAction(ManagerRegistry $doctrine, Request $request):Response
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
            $data = $form->getData();
            dump($data);die;
        }


         return $this->render('account/register.html.twig', [
            'form' => $form->createView()
        ]);
    }


}
