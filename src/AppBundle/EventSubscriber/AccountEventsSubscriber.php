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
        dump($event);
        exit;
    }
}