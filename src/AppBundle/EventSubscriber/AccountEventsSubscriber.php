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
use AppBundle\Events\AccountForgotPasswordEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use AppBundle\Entity\User;
use AppBundle\Entity\UserToken;
use Doctrine\Common\Persistence\ManagerRegistry;


class AccountEventsSubscriber implements EventSubscriberInterface
{
    private $mailer;
    private $twig;
    private $doctrine;

    public function __construct(\Swift_Mailer $mailer, \Twig_Environment $twig, ManagerRegistry $doctrine)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
        $this->doctrine = $doctrine;
    }

    public static function getSubscribedEvents()
    {
        /*
         * doit retourner un tableau
         *      clé : l'événement écouté
         *      valeur : nom du gestionnaire d'événement
         */
        
        return [
            AccountEvents::CREATE => 'create',
            AccountEvents::PASSWORD_FORGOT => 'passwordForgot',
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

    }


    public function passwordForgot (AccountForgotPasswordEvent $event)
    {
        //tester l'existence de l'email dans la base
        $user = $this->doctrine
            ->getRepository(User::class)
            ->findOneBy(['email' => $event->getPostData()->getUserEmail()])
        ;

        $now = new \Datetime();
//        dump($event->getPostData()->getUserEmail());die;

        $userToken = $this->doctrine
            ->getRepository(UserToken::class)
            ->findOneBy(['userEmail' => $event->getPostData()->getUserEmail()])
        ;

//        if($userToken){
//            $this->doctrine->getManager()->remove($userToken);
//            $this->doctrine->getManager()->flush();
//        }


        if ($user && !$userToken){
            $data = $event->getPostData();
            //init token
            $token = bin2hex(random_bytes(10));
            $data->setToken($token);
            //initdate
            $date = new \Datetime('+1 day');
            $data->setExpirationDate($date);

            //on insère dans UserToken
            $em = $this->doctrine->getManager();
            $em->persist($data);
            $em->flush();

            $emailTemplate = $this->twig->render('emailing/account.password.forgot.html.twig', [
                'token' => $data->getToken()
            ]);

            $message = (new \Swift_Message("objet du message Reinitialisation"))
                ->setFrom('contact@website.com')
                ->setTo($event->getPostData()->getUserEmail())
                ->setBody(
                    $emailTemplate, 'text/html'
                )
            ;

            //envoi email
            $this->mailer->send($message);
        }
    }



}