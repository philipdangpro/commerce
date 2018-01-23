<?php
/**
 * Created by PhpStorm.
 * User: wabap2-14
 * Date: 22/01/18
 * Time: 16:19
 * à remove dans Go to Settings -> Editor -> File and Code Templates -> Includes (TAB) -> PHP File Header
 */

namespace AppBundle\EventSubscriber;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\RequestStack;


class UserTypeSubscriber implements EventSubscriberInterface
{
//    private $requestStack;
    private $request;

    public function __construct(RequestStack $requestStack)
    {
//        $this->requestStack = $requestStack;
        $this->request = $requestStack->getMasterRequest();
    }

    public static function getSubscribedEvents(){
        return [
            FormEvents::PRE_SET_DATA => 'preSetData'
        ];
    }

    public function preSetData(FormEvent $event)
    {
        //récupérer la route
        $route = $this->request->get('_route');

        //récupération de la saisie
        $data = $event->getData();

        //formulaire
        $form = $event->getForm();

        //tester la route
        if($route === 'profile.manage.index'){
            $form->remove('username');
            $form->remove('password');
            $form->remove('email');
        }

        dump('pre set data');
    }
}