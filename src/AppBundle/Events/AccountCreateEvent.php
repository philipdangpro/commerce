<?php
/**
 * Created by PhpStorm.
 * User: wabap2-14
 * Date: 17/01/18
 * Time: 16:03
 * à remove dans Go to Settings -> Editor -> File and Code Templates -> Includes (TAB) -> PHP File Header
 */

namespace AppBundle\Events;

use Symfony\Component\EventDispatcher\Event;

class AccountCreateEvent extends Event
{
     /*
      * l'événement sert d'interface entre le déclencheur et le souscripteur
      * */
     private $user;

     private $userType;

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getUserType()
    {
        return $this->userType;
    }

    /**
     * @param mixed $userType
     */
    public function setUserType($userType)
    {
        $this->userType = $userType;
    }



}