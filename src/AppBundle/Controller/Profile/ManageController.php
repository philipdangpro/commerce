<?php

namespace AppBundle\Controller\Profile;

use AppBundle\Entity\Category;
use AppBundle\Entity\Product;
use Doctrine\Common\Persistence\ManagerRegistry;
use MongoDB\Driver\Manager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/profile")
 */

class ManageController extends Controller
{
    /**
     * @Route("/manage", name="profile.manage.index")
     */
    public function indexAction():Response
    {
        return $this->render('profile/manage/index.html.twig',[

        ]);
    }


}
