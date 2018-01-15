<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class HomepageController extends Controller
{
    /**
     * @Route("/", name="homepage.index")
     */
    public function indexAction()
    {
        return $this->render('homepage/index.html.twig', [

        ]);
    }
}
