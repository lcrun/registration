<?php

namespace Acme\DemoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

    /**
     * @ORM\Entity
     * @ORM\Table(name="sign_up")
     * @UniqueEntity(
     *     fields={"user", "conference"},
     *     errorPath="user",
     *     message="This user is already in use on that conference."
     * )
     */
class SignUp
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    
    
    
    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="signUps")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;
    
    
    /**
     * @ORM\ManyToOne(targetEntity="Conference", inversedBy="signUps")
     * @ORM\JoinColumn(name="conference_id", referencedColumnName="id")
     */
    protected $conference;
    
    
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="signUpDate", type="datetime")
     */
    private $signUpDate;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set signUpDate
     *
     * @param \DateTime $signUpDate
     *
     * @return SignUp
     */
    public function setSignUpDate($signUpDate)
    {
        $this->signUpDate = $signUpDate;

        return $this;
    }

    /**
     * Get signUpDate
     *
     * @return \DateTime
     */
    public function getSignUpDate()
    {
        return $this->signUpDate;
    }
    


  
    /**
     * Set user
     *
     * @param \Acme\DemoBundle\Entity\User $user
     *
     * @return SignUp
     */
    public function setUser(\Acme\DemoBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Acme\DemoBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
       
    }
    
    
     /**
     * Set conference
     *
     * @param \Acme\DemoBundle\Entity\Conference $conference
     *
     * @return SignUp
     */
    public function setConference(\Acme\DemoBundle\Entity\Conference $conference = null)
    {
        $this-> conference =  $conference;

        return $this;
    }

    /**
     * Get conference
     *
     * @return \Acme\DemoBundle\Entity\Conference
     */
    public function getConference()
    {
        return $this->conference;
    }
}
