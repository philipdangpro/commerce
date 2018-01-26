<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use AppBundle\Entity\Product;
use Doctrine\Common\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;


class SearchController extends Controller
{
    /**
     * @Route("/search", name="search.index")
     */
    public function indexAction(ManagerRegistry $doctrine):Response
    {
        //on get toutes les catégories
        $categories = $doctrine
            ->getRepository(Category::class)
            ->findAll();

        //on get tous les products
        $products = $doctrine
            ->getRepository(Product::class)
            ->findAll();

        return $this->render('search/index.html.twig', [
            'categories' => $categories,
            'products' => $products
        ]);
    }


}
