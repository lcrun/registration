<?php

namespace Acme\DemoBundle\Entity;

/**
 * conference
 */
class Conference
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $conferenceName;

    /**
     * @var \DateTime
     */
    private $dueDate;

    /**
     * @var text
     */
    private $detail;


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
     * Set conferenceName
     *
     * @param string $conferenceName
     *
     * @return conference
     */
    public function setConferenceName($conferenceName)
    {
        $this->conferenceName = $conferenceName;

        return $this;
    }

    /**
     * Get conferenceName
     *
     * @return string
     */
    public function getConferenceName()
    {
        return $this->conferenceName;
    }

    /**
     * Set dueDate
     *
     * @param \DateTime $dueDate
     *
     * @return conference
     */
    public function setDueDate($dueDate)
    {
        $this->dueDate = $dueDate;

        return $this;
    }

    /**
     * Get dueDate
     *
     * @return \DateTime
     */
    public function getDueDate()
    {
        return $this->dueDate;
    }

    /**
     * Set detail
     *
     * @param string $detail
     *
     * @return conference
     */
    public function setDetail($detail)
    {
        $this->detail = $detail;

        return $this;
    }

    /**
     * Get detail
     *
     * @return text
     */
    public function getDetail()
    {
        return $this->detail;
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $signUps;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->signUps = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add signUp
     *
     * @param \Acme\DemoBundle\Entity\SignUp $signUp
     *
     * @return Conference
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
    private $schedule;


    /**
     * Set schedule
     *
     * @param string $schedule
     *
     * @return Conference
     */
    public function setSchedule($schedule)
    {
        $this->schedule = $schedule;

        return $this;
    }

    /**
     * Get schedule
     *
     * @return string
     */
    public function getSchedule()
    {
        return $this->schedule;
    }
}
