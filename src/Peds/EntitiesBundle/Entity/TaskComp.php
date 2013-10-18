<?php

namespace Peds\EntitiesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TaskComp
 */
class TaskComp
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $obs;

    /**
     * @var \Peds\EntitiesBundle\Entity\User
     */
    private $user;

    /**
     * @var \Peds\EntitiesBundle\Entity\Task
     */
    private $task1;

    /**
     * @var \Peds\EntitiesBundle\Entity\Task
     */
    private $task2;

    /**
     * @var \Peds\EntitiesBundle\Entity\Matching
     */
    private $matching;


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
     * Set obs
     *
     * @param string $obs
     * @return TaskComp
     */
    public function setObs($obs)
    {
        $this->obs = $obs;
    
        return $this;
    }

    /**
     * Get obs
     *
     * @return string 
     */
    public function getObs()
    {
        return $this->obs;
    }

    /**
     * Set user
     *
     * @param \Peds\EntitiesBundle\Entity\User $user
     * @return TaskComp
     */
    public function setUser(\Peds\EntitiesBundle\Entity\User $user = null)
    {
        $this->user = $user;
    
        return $this;
    }

    /**
     * Get user
     *
     * @return \Peds\EntitiesBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set task1
     *
     * @param \Peds\EntitiesBundle\Entity\Task $task1
     * @return TaskComp
     */
    public function setTask1(\Peds\EntitiesBundle\Entity\Task $task1 = null)
    {
        $this->task1 = $task1;
    
        return $this;
    }

    /**
     * Get task1
     *
     * @return \Peds\EntitiesBundle\Entity\Task 
     */
    public function getTask1()
    {
        return $this->task1;
    }

    /**
     * Set task2
     *
     * @param \Peds\EntitiesBundle\Entity\Task $task2
     * @return TaskComp
     */
    public function setTask2(\Peds\EntitiesBundle\Entity\Task $task2 = null)
    {
        $this->task2 = $task2;
    
        return $this;
    }

    /**
     * Get task2
     *
     * @return \Peds\EntitiesBundle\Entity\Task 
     */
    public function getTask2()
    {
        return $this->task2;
    }

    /**
     * Set matching
     *
     * @param \Peds\EntitiesBundle\Entity\Matching $matching
     * @return TaskComp
     */
    public function setMatching(\Peds\EntitiesBundle\Entity\Matching $matching = null)
    {
        $this->matching = $matching;
    
        return $this;
    }

    /**
     * Get matching
     *
     * @return \Peds\EntitiesBundle\Entity\Matching 
     */
    public function getMatching()
    {
        return $this->matching;
    }
    /**
     * @var \Peds\EntitiesBundle\Entity\Task
     */
    private $baseTask;

    /**
     * @var \Peds\EntitiesBundle\Entity\ReferenceProcess
     */
    private $rp;


    /**
     * Set baseTask
     *
     * @param \Peds\EntitiesBundle\Entity\Task $baseTask
     * @return TaskComp
     */
    public function setBaseTask(\Peds\EntitiesBundle\Entity\Task $baseTask = null)
    {
        $this->baseTask = $baseTask;
    
        return $this;
    }

    /**
     * Get baseTask
     *
     * @return \Peds\EntitiesBundle\Entity\Task 
     */
    public function getBaseTask()
    {
        return $this->baseTask;
    }

    /**
     * Set rp
     *
     * @param \Peds\EntitiesBundle\Entity\ReferenceProcess $rp
     * @return TaskComp
     */
    public function setRp(\Peds\EntitiesBundle\Entity\ReferenceProcess $rp = null)
    {
        $this->rp = $rp;
    
        return $this;
    }

    /**
     * Get rp
     *
     * @return \Peds\EntitiesBundle\Entity\ReferenceProcess 
     */
    public function getRp()
    {
        return $this->rp;
    }
}