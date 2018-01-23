<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Category;
use AppBundle\Entity\Product;
use AppBundle\Form\CategoryType;
use Doctrine\Common\Persistence\ManagerRegistry;
use MongoDB\Driver\Manager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/admin")
 */

class HomepageController extends Controller
{
    /**
     * @Route("/", name="admin.homepage.index")
     */
    public function indexAction(ManagerRegistry $doctrine, Request $request):Response
    {
        $entity = new Category();
        $type = CategoryType::class;
        $form = $this->createForm($type, $entity);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $data = $form->getData();
            dump($data);
            die;

        }

        return $this->render('admin/homepage/index.html.twig',[

        ]);
    }


}
