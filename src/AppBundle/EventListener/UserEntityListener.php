<?php
/**
 * Created by PhpStorm.
 * User: wabap2-14
 * Date: 17/01/18
 * Time: 10:32
 * à remove dans Go to Settings -> Editor -> File and Code Templates -> Includes (TAB) -> PHP File Header
 */

namespace AppBundle\EventListener;

use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use AppBundle\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;

class UserEntityListener
{
    /*
     * injecter un service
     *      créer une propriété
     *      créer un constructeur
     *
     */
    private $encoder;

    public function __construct(UserPasswordEncoder $encoder)
    {
        $this->encoder = $encoder;
    }

    /*
     * le nom des méthodes doivent reprendre le nom de l'événement écouté
     * paramètres:
     *     - instance de l'entité écouté
     *     - argument différent selon l'événement écouté
     */
    public function prePersist(User $user, LifecycleEventArgs $args){
        // récuperation du mot de passe en clair
        $plainPassword = $user->getPassword();

        //encodage
        $encodedPassword = $this->encoder->encodePassword($user, $plainPassword);

        //màj du mdp
        $user->setPassword($encodedPassword);

    }
}
