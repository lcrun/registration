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
}
