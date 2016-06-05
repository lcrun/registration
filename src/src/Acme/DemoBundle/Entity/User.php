<?php
// src/AppBundle/Entity/User.php

namespace Acme\DemoBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @ORM\OneToMany(targetEntity="SignUp", mappedBy="user")
     */
    protected $signUps;
    
    public function __construct()
    {  
        parent::__construct();
        $this->signUps = new ArrayCollection();
        
    }

    

    /**
     * Add signUp
     *
     * @param \Acme\DemoBundle\Entity\SignUp $signUp
     *
     * @return User
     */
    public function addSignUp(\Acme\DemoBundle\Entity\SignUp $signUp)
    {
        $this->signUps[] = $signUp;

        return $this;
    }

    /**
     * Remove signUp
     *
     * @param \Acme\DemoBundle\Entity\SignUp $signUp
     */
    public function removeSignUp(\Acme\DemoBundle\Entity\SignUp $signUp)
    {
        $this->signUps->removeElement($signUp);
    }

    /**
     * Get signUps
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSignUps()
    {
        return $this->signUps;
    }
    /**
     * @var string
     */
    private $gender;

    /**
     * @var string
     */
    private $company;

    /**
     * @var string
     */
    private $position;

    /**
     * @var string
     */
    private $address;


    /**
     * Set gender
     *
     * @param string $gender
     *
     * @return User
     */
    public function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * Get gender
     *
     * @return string
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Set company
     *
     * @param string $company
     *
     * @return User
     */
    public function setCompany($company)
    {
        $this->company = $company;

        return $this;
    }

    /**
     * Get company
     *
     * @return string
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * Set position
     *
     * @param string $position
     *
     * @return User
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return string
     */
    public function getPosition()
    {
        return $this->position;
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
     * @var string
     */
    private $phone;


    /**
     * Set phone
     *
     * @param string $phone
     *
     * @return User
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }
    /**
     * @var string
     */
    private $name;


    /**
     * Set name
     *
     * @param string $name
     *
     * @return User
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}
