<?php

namespace AppBundle\Controller;

//use AppBundle\Entity\Category;
//use AppBundle\Entity\Product;
//use Doctrine\Common\Persistence\ManagerRegistry;
//use MongoDB\Driver\Manager;
use AppBundle\Entity\Category;
use AppBundle\Entity\Currency;
use Doctrine\Common\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;


/**
 * @Route("/ajax")
 */

class AjaxController extends Controller
{

    /**
     * @Route("/search", name="ajax.search")
     */
    public function searchAction(ManagerRegistry $doctrine, Request $request)//:Response
    {
        //récupération de la variable POST envoyée en JS
        $selectValue = $request->get('selectValue');

        //produits de la catégorie
        $category = $doctrine
            ->getRepository(Category::class)
            ->find($selectValue);

        //supprimer les références circulaires avec les propriétés bidirectionnelles
        $objectNormalizer = new ObjectNormalizer();
        //en argument de setCircularReferenceHandler(il faut mettre une fonction qui retourne un objet), un callable, c'est une fonction qui va être appellée(callée) quand on va l'utiliser, c'est une référence à une fonction, ici, on aurait pu écrire juste le nom de la fonction et l'écrire autre part
        $objectNormalizer->setCircularReferenceHandler(function($obj){
            return $obj;
        });

        /*
         * normalizers : format d'entrée des données
         * encoders : format de sortie des données
         */

        $normalizers = [ $objectNormalizer ]; //on initie l'entrée
        $encoders = [ new JsonEncoder(), new XmlEncoder() ]; //on initie la sortie

        // serializer, on initie le sérialisateur, l'objet qui va faire les conversions
        $serializer = new Serializer($normalizers, $encoders);

        //sérialisation
        $results = $serializer->serialize($category, 'json');

        $response = new Response($results);

// il serait possible de renvoyer le code html directement mais ça serait bien trop lourd, à transporter sur les réseaux
//        return $this->render($htmlDejaRegenere);

        return $response;

    }



    /**
     * @Route("/cookies-disclaimer", name="ajax.cookies.disclaimer")
     */
    public function cookiesDisclaimerAction(Request $request, SessionInterface $session):JsonResponse
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


    /**
     * @Route("/currency", name="ajax.currency")
     */
    public function currencyAction(ManagerRegistry $doctrine, Request $request, SessionInterface $session):Response
    {
        $selectValue = $request->get('selectValue');

        //produits de la catégorie
        $currency = $doctrine
            ->getRepository(Currency::class)
            ->findOneBy(['base' => $selectValue]);

        //supprimer les références circulaires avec les propriétés bidirectionnelles
        $objectNormalizer = new ObjectNormalizer();

        $objectNormalizer->setCircularReferenceHandler(function($obj){
            return $obj;
        });

        $session->set('rate', $currency->getRate());

        $normalizers = [ $objectNormalizer ]; //on initie l'entrée
        $encoders = [ new JsonEncoder(), new XmlEncoder() ]; //on initie la sortie

        $serializer = new Serializer($normalizers, $encoders);

        $results = $serializer->serialize($currency, 'json');

        return $this->render('search/index.html.twig', [
            'currency' => $currency

        ]);

        die;


        $response = new Response($results);

        return $response;


    }





}