<?php

namespace Peds\EntitiesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TaskMapping
 */
class TaskMapping
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $tcompId;

    /**
     * @var integer
     */
    private $taskId;


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
     * Set tcompId
     *
     * @param integer $tcompId
     * @return TaskMapping
     */
    public function setTcompId($tcompId)
    {
        $this->tcompId = $tcompId;
    
        return $this;
    }

    /**
     * Get tcompId
     *
     * @return integer 
     */
    public function getTcompId()
    {
        return $this->tcompId;
    }

    /**
     * Set taskId
     *
     * @param integer $taskId
     * @return TaskMapping
     */
    public function setTaskId($taskId)
    {
        $this->taskId = $taskId;
    
        return $this;
    }

    /**
     * Get taskId
     *
     * @return integer 
     */
    public function getTaskId()
    {
        return $this->taskId;
    }
    /**
     * @var \Peds\EntitiesBundle\Entity\Task
     */
    private $task;

    /**
     * @var \Peds\EntitiesBundle\Entity\TaskComp
     */
    private $tcomp;


    /**
     * Set task
     *
     * @param \Peds\EntitiesBundle\Entity\Task $task
     * @return TaskMapping
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

    /**
     * Set tcomp
     *
     * @param \Peds\EntitiesBundle\Entity\TaskComp $tcomp
     * @return TaskMapping
     */
    public function setTcomp(\Peds\EntitiesBundle\Entity\TaskComp $tcomp = null)
    {
        $this->tcomp = $tcomp;
    
        return $this;
    }

    /**
     * Get tcomp
     *
     * @return \Peds\EntitiesBundle\Entity\TaskComp 
     */
    public function getTcomp()
    {
        return $this->tcomp;
    }
	public function __toString()
    {
        return $this->task->shortName;
    }
}