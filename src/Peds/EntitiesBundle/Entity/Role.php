<?php

namespace Peds\EntitiesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Role
 */
class Role
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $shortName;

    /**
     * @var \Peds\EntitiesBundle\Entity\Task
     */
    private $task;


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
     * Set description
     *
     * @param string $description
     * @return Role
     */
    public function setDescription($description)
    {
        $this->description = $description;
    
        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set shortName
     *
     * @param string $shortName
     * @return Role
     */
    public function setShortName($shortName)
    {
        $this->shortName = $shortName;
    
        return $this;
    }

    /**
     * Get shortName
     *
     * @return string 
     */
    public function getShortName()
    {
        return $this->shortName;
    }

    /**
     * Set task
     *
     * @param \Peds\EntitiesBundle\Entity\Task $task
     * @return Role
     */
    public function setTask(\Peds\EntitiesBundle\Entity\Task $task = null)
    {
        $this->task = $task;
    
        return $this;
    }

    /**
     * Get task
     *
     * @return \Peds\EntitiesBundle\Entity\Task 
     */
    public function getTask()
    {
        return $this->task;
    }

    public function __toString()
    {
        return $this->shortName;
    }
}