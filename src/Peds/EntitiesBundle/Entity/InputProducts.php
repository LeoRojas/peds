<?php

namespace Peds\EntitiesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * InputProducts
 */
class InputProducts
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Peds\EntitiesBundle\Entity\Product
     */
    private $product;

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
     * Set product
     *
     * @param \Peds\EntitiesBundle\Entity\Product $product
     * @return InputProducts
     */
    public function setProduct(\Peds\EntitiesBundle\Entity\Product $product = null)
    {
        $this->product = $product;
    
        return $this;
    }

    /**
     * Get product
     *
     * @return \Peds\EntitiesBundle\Entity\Product 
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * Set task
     *
     * @param \Peds\EntitiesBundle\Entity\Task $task
     * @return InputProducts
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
}