<?php

namespace Peds\EntitiesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Activity
 */
class Activity
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
     * @var \Peds\EntitiesBundle\Entity\ReferenceProcess
     */
    private $rp;


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
     * @return Activity
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
     * @return Activity
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
     * Set rp
     *
     * @param \Peds\EntitiesBundle\Entity\ReferenceProcess $rp
     * @return Activity
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
    public function __toString()
    {
        return $this->shortName;
    }
}