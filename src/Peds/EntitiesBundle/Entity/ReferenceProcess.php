<?php

namespace Peds\EntitiesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ReferenceProcess
 */
class ReferenceProcess
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $shortName;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $workflow;

    /**
     * @var string
     */
    private $visibility;

    /**
     * @var \Peds\EntitiesBundle\Entity\User
     */
    private $owner;


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
     * Set shortName
     *
     * @param string $shortName
     * @return ReferenceProcess
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
     * Set description
     *
     * @param string $description
     * @return ReferenceProcess
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
     * Set workflow
     *
     * @param string $workflow
     * @return ReferenceProcess
     */
    public function setWorkflow($workflow)
    {
        $this->workflow = $workflow;
    
        return $this;
    }

    /**
     * Get workflow
     *
     * @return string 
     */
    public function getWorkflow()
    {
        return $this->workflow;
    }

    /**
     * Set visibility
     *
     * @param string $visibility
     * @return ReferenceProcess
     */
    public function setVisibility($visibility)
    {
        $this->visibility = $visibility;
    
        return $this;
    }

    /**
     * Get visibility
     *
     * @return string 
     */
    public function getVisibility()
    {
        return $this->visibility;
    }

    /**
     * Set owner
     *
     * @param \Peds\EntitiesBundle\Entity\User $owner
     * @return ReferenceProcess
     */
    public function setOwner(\Peds\EntitiesBundle\Entity\User $owner = null)
    {
        $this->owner = $owner;
    
        return $this;
    }

    /**
     * Get owner
     *
     * @return \Peds\EntitiesBundle\Entity\User 
     */
    public function getOwner()
    {
        return $this->owner;
    }
    public function __toString()
    {
        return $this->shortName;
    }
	/*
	public function __construct(){
		 $this->workflow = '0';
		 $this->visibility = 'PUBLIC';
    }
	*/
}