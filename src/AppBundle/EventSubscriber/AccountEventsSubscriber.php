<?php
/**
 * Created by PhpStorm.
 * User: wabap2-14
 * Date: 17/01/18
 * Time: 16:12
 * à remove dans Go to Settings -> Editor -> File and Code Templates -> Includes (TAB) -> PHP File Header
 */
namespace  AppBundle\EventSubscriber;

use AppBundle\Events\AccountCreateEvent;
use AppBundle\Events\AccountEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class AccountEventsSubscriber implements EventSubscriberInterface
{
    private $mailer;
    private $twig;

    public function __construct(\Swift_Mailer $mailer, \Twig_Environment $twig)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    public static function getSubscribedEvents()
    {
        /*
         * doit retourner un tableau
         *      clé : l'événement écouté
         *      valeur : nom du gestionnaire d'événement
         */
        
        return [
            AccountEvents::CREATE => 'create'
        ];
    }

    /*
     * un gestionnaire d'événement reçoit l'événement en paramètre
     * ce $event doit contenir toutes les infos pour exécuter l'action create
     */
    public function create(AccountCreateEvent $event)
    {
        /*
         * envoi mail
         *      service d'emailing : SwiftMailer
         *      message: Swift_Message
         *      $mailer->send(): envoie le message
         *      setFrom est une méthode obligatoire
         *          par défaut: type text/plain : texte simple, non enrichi
         */

        $emailTemplate = $this->twig->render('emailing/account.create.html.twig', [ 'data' => $event->getUser()]);

        $message = (new \Swift_Message("objet du message"))
            //c'est le site qui envoie le mail
            ->setFrom('contact@website.com')
            ->setTo($event->getUser()->getEmail())
            //->setBody('<h1 style="color:red;">Bienvenue' . $event->getUser()->getUsername(). '</h1>', 'text/html')
            ->setBody(
                $emailTemplate, 'text/html'
            )
            ->addPart(
                $this->twig->render('emailing/account.create.txt.twig',
                    ['data' => $event->getUser()]
                )
            )
        ;

        //envoi email
        $this->mailer->send($message);



        dump($event);
//        exit;
    }
}