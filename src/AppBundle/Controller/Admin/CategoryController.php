<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Category;
use Doctrine\Common\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/admin")
 */

class CategoryController extends Controller
{
    /**
     * @Route("/category", name="admin.category.index")
     */
    public function indexAction(ManagerRegistry $doctrine):Response
    {
        //on écrit le CRUD RepoClass = repository::class, c'est pour les select
        $rc = $doctrine
            ->getRepository(Category::class);
        $results = $rc->findAll();

        return $this->render('admin/category/index.html.twig',[
            'results' => $results
        ]);
    }

    /**
     * @Route("/category/form", name="admin.category.form")
     */
    public function formAction(ManagerRegistry $doctrine, Request $request):Response
    {



        return $this->render('admin/category/form.html.twig',[

        ]);
    }
}
