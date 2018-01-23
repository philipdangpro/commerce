<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
* @ORM\Table(name="user")
* @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
* @ORM\EntityListeners({"AppBundle\EventListener\UserEntityListener"})
*/
class User implements UserInterface
{
    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }
/**
* @ORM\Column(type="integer")
* @ORM\Id
* @ORM\GeneratedValue(strategy="AUTO")
*/
private $id;

/**
* @ORM\Column(type="string", length=25, unique=true)
*/
private $username;

/**
* @ORM\Column(type="string", length=64)
*/
private $password;

/**
* @ORM\Column(type="string", length=60, unique=true)
*/
private $email;

/**
 * @ORM\Column(type="string", length=150, nullable=true)
 */
private $address;

/**
 * @ORM\Column(type="string", length=5, nullable=true)
 */
private $zipcode;

/**
 * @ORM\Column(type="string", length=50, nullable=true)
 */
private $city;

/**
 * @ORM\Column(type="string", length=150, nullable=true)
 */
private $country;


    /**
* @ORM\Column(name="is_active", type="boolean")
*/
private $isActive;

public function __construct()
{
$this->isActive = true;
// may not be needed, see section on salt below
// $this->salt = md5(uniqid('', true));
}

public function getUsername()
{
return $this->username;
}

public function getSalt()
{
// you *may* need a real salt depending on your encoder
// see section on salt below
return null;
}

public function getPassword()
{
return $this->password;
}

public function getRoles()
{
return array('ROLE_USER');
}

public function eraseCredentials()
{
}

/** @see \Serializable::serialize() */
public function serialize()
{
return serialize(array(
$this->id,
$this->username,
$this->password,
// see section on salt below
// $this->salt,
));
}

/** @see \Serializable::unserialize() */
public function unserialize($serialized)
{
list (
$this->id,
$this->username,
$this->password,
// see section on salt below
// $this->salt
) = unserialize($serialized);
}

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getisActive()
    {
        return $this->isActive;
    }

    /**
     * @param mixed $isActive
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
    }


    /**
     * @param mixed $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }



    /**
     * Set password
     *
     * @param string $password
     *
     * @return User
     */
    public function setPassword($password):User
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Set address
     *
     * @param string $address
     *
     * @return User
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set zipcode
     *
     * @param string $zipcode
     *
     * @return User
     */
    public function setZipcode($zipcode)
    {
        $this->zipcode = $zipcode;

        return $this;
    }

    /**
     * Get zipcode
     *
     * @return string
     */
    public function getZipcode()
    {
        return $this->zipcode;
    }

    /**
     * Set city
     *
     * @param string $city
     *
     * @return User
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set country
     *
     * @param string $country
     *
     * @return User
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }
}
