<?php

namespace AppBundle\EventSubscriber;


//use Symfony\Component\BrowserKit\Response;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Event\KernelEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Yaml\Parser;


class KernelEventsSubscriber implements EventSubscriberInterface
{
    private $twig;
    private $session;
    private $maintenanceEnable;

    public function __construct(\Twig_Environment $twig, SessionInterface $session, bool $maintenanceEnable)
    {
        $this->twig = $twig;
        $this->maintenanceEnable = $maintenanceEnable;
        $this->session = $session;

    }


    public static function getSubscribedEvents()
    {
        /*
         * définir plusieurs méthodes à un évt
         *      créer un array d'array : paramètres
         *          - nom de la mthd
         *          - priorité de l'event (par défaut 0)
         */
        return [
            KernelEvents::REQUEST => 'maintenanceMode',
            KernelEvents::RESPONSE => [
                ['redirect404', 100],
                ['addSecurityHeaders'],
                ['cookiesDisclaimer'],
            ]
        ];
    }

    public function cookiesDisclaimer(FilterResponseEvent $event)
    {

        if(!$this->session->has('cookieDisclaimer')) {
            $this->session->set('cookieDisclaimer', true);
        }

        //récupération du contenu
        $content = $event->getResponse()->getContent();

        $sessionValue = $this->session->get('cookieDisclaimer');

//        dump($sessionValue);

        if($sessionValue === true ){
            $toto = '<div class="alert alert-warning alert-dismissible" role="alert">
                      <button type="button" class="close close-cookies-disclaimer" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                      <strong>Attention!</strong>Ce site utilise des cookies
                     </div>';

            //replacement de contenu
            $content = str_replace('<body>', '<body><h1>' . $toto .'</h1>', $content);
        }

        $response = new Response($content, 200);
        $event->setResponse($response);


    }

    public function addSecurityHeaders(FilterResponseEvent $event)
    {
        //réponse
        $response = new Response(
            $event->getResponse()->getContent(),
            $event->getResponse()->getStatusCode(),
            [
//              'Content-Security-Policy' => 'default-src https:'
                'Content-Security-Policy' => "default-src 'self'",
                'X-Content-Type-Options' => 'nosniff',
                'X-Frame-Options' => 'DENY',

            ]
        );

        //retourner la réponse
        $event->setResponse($response);
    }

    public function redirect404(FilterResponseEvent $event)
    {
        //code HTTP
        $code = $event->getResponse()->getStatusCode();

        //réponse
        $response = new RedirectResponse('/fr/');

        //retourner la réponse

//        dump($code);
//        die;
        if($code == '404'){
//            $this->session->getFlashBag()->set('notice', 'toto');
//            dump($this->session->getFlashBag()->get('notice'));
//            die;
            $event->setResponse($response);
//            $event->setResponse('maintenance/404.html.twig', []);
        }
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