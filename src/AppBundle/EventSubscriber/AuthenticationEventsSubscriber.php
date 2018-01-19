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
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\AuthenticationEvents;
use Symfony\Component\Security\Core\Event\AuthenticationFailureEvent;

class AuthenticationEventsSubscriber implements EventSubscriberInterface
{
    private $session;
    private $maxAuthenticationFailure;

    public function __construct(SessionInterface $session, int $maxAuthenticationFailure)
    {
        $this->session = $session;
        $this->maxAuthenticationFailure = $maxAuthenticationFailure;
    }


    public static function getSubscribedEvents()
    {
        return [
            AuthenticationEvents::AUTHENTICATION_FAILURE => 'authenticationFailure',
            
        ];
    }

    public function authenticationFailure(AuthenticationFailureEvent $event)
    {
        /*
         * session : ne pas utiliser $_SESSION
         *      service : sessionInterface
         *          méthodes
         *              set(key, value): créer une entrée
         *              get(key) :  récupérer la valeur d'une entrée
         *              has(key) : tester l'existence d'une clé
         *              remove(key): supprimer une entrée
         *              clear(): correspond à session unset
         *              invalidate(): arrête la session, mais la session n'est pas détruite
         */

        //tester si la clé existe
        if($this->session->has('authentication_failure')){
            //si les 3 échecs ne sont pas atteints
            $value = $this->session->get('authentication_failure');

            if($value < $this->maxAuthenticationFailure){
                $value += 1;
                $this->session->set('authentication_failure', $value);
            }
            //récupération de la valeur
            $value = $this->session->get('authentication_failure');

            dump('il faut changer de mail');
        } else {
            $this->session->set('authentication_failure', 1);
        }


    }


}