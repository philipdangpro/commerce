<?php

namespace AppBundle\EventSubscriber;


//use Symfony\Component\BrowserKit\Response;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Event\KernelEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Yaml\Parser;


class KernelEventsSubscriber implements EventSubscriberInterface
{
    private $twig;
    public function __construct(\Twig_Environment $twig, bool $maintenanceEnable)
    {
        $this->twig = $twig;
        $this->maintenanceEnable = $maintenanceEnable;
    }


    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => 'maintenanceMode'
        ];
    }

    public function maintenanceMode(GetResponseEvent $event)
    {
        //contenu de la réponse

        if($this->maintenanceEnable){
            $content = $this->twig->render('maintenance/index.html.twig', ['yes' => 'yessai!']);

//        $coco = new Parser();
//        $content =$coco->parseFile(__DIR__ . '/../../../app/config/routing.yml');
//        dump(__DIR__ . '/../../../app/config/routing.yml'); exit;

        /*
         * modifier ou remplacer la réponse
         *      $event->setResponse() : envoi d'une nouvelle réponse (Response / RedirectResponse / JsonResponse)
         * */
        $response = new Response($content, 503);

        //retourner la réponse
        $event->setResponse($response);
        }


    }

}