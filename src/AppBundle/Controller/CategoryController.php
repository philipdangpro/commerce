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

class CategoryController extends Controller
{
    /**
     * @Route("/category/{slug}", name="homepage.category")
     */
    public function indexAction(ManagerRegistry $doctrine, Request $request, $slug):Response
    {
        $locale = $request->getLocale();
//        $id = ($id > 0 ) ? $id : 33;

        $category = $doctrine
            ->getRepository(Category::class)
            ->findBy(['slug' => $slug]);

        $products = $doctrine
            ->getRepository(Product::class)
            ->getProductsByCat($locale, $slug);

        return $this->render('category/index.html.twig', [
            'products' => $products,
            'category' => $category
        ]);
    }
}
