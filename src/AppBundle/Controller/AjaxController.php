<?php

namespace AppBundle\Controller;

//use AppBundle\Entity\Category;
//use AppBundle\Entity\Product;
//use Doctrine\Common\Persistence\ManagerRegistry;
//use MongoDB\Driver\Manager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;


/**
 * @Route("/ajax")
 */

class AjaxController extends Controller
{
    /**
     * @Route("/cookies-disclaimer", name="ajax.cookies.disclaimer")
     */
    public function cookiesDisclaimerAction(Request $request, SessionInterface $session)//:JsonResponse
    {
        //récupération de la variable POST envoyée par le JS
        $disclaimerValue = $request->get('disclaimerValue');
        dump($request);

        /*
         * route appelée en AJAX avec symfony
         *          - pas de vue
         *          - la route retourne du Json avec JsonResponse
         */

        //modification de la valeur en session
        $session->set('cookieDisclaimer', $disclaimerValue);

        $response = new JsonResponse([
            'success' => 'OK'
        ]);
        //car c'est un objet de type JsonResponse, la réponse retournée est forcément formatée/sérialisée
        return $response;
    }


}
//
//
////récupération de la langue
//$locale = $request->getLocale();
//
////récupération des catégories
//$categories = $doctrine
//    ->getRepository(Category::class)
//    ->getCategoriesByLocaleWithProductsCount($locale)
//;
//
//$randomProductsByCategories = $doctrine
//    ->getRepository(Product::class)
//    ->getNRandomProductsByCategory($locale);
//
//return $this->render('homepage/index.html.twig', [
//    'categories' => $categories,
//    'rndprod' => $randomProductsByCategories
//]);