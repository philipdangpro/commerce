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

class ProductController extends Controller
{
    /**
     * @Route("/product/{slug}", name="homepage.product")
     */
    public function indexAction(ManagerRegistry $doctrine, Request $request, $slug):Response
    {
        $locale = $request->getLocale();

        $product = $doctrine
            ->getRepository(Product::class)
            ->getInfo($locale, $slug);

        return $this->render('product/index.html.twig', [
            'product' => $product
        ]);
    }
}
