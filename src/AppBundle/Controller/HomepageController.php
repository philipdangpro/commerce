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

class HomepageController extends Controller
{
    /**
     * @Route("/", name="homepage.index")
     */
    public function indexAction(ManagerRegistry $doctrine, Request $request):Response
    {
        //récupération de la langue
        $locale = $request->getLocale();

        //récupération des catégories
        $categories = $doctrine
            ->getRepository(Category::class)
            ->getCategoriesByLocaleWithProductsCount($locale)
        ;

        $randomProductsByCategories = $doctrine
            ->getRepository(Product::class)
            ->getNRandomProductsByCategory($locale);

        return $this->render('homepage/index.html.twig', [
            'categories' => $categories,
            'rndprod' => $randomProductsByCategories
        ]);
    }


}
