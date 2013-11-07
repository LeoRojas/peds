<?php

namespace Peds\EntitiesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Product
 */
class Product
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
     * @return Product
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
     * @return Product
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
    public function __toString()
    {
        return $this->shortName;
    }
    /**
     * @var \Peds\EntitiesBundle\Entity\ReferenceProcess
     */
    private $rp;


    /**
     * Set rp
     *
     * @param \Peds\EntitiesBundle\Entity\ReferenceProcess $rp
     * @return Product
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