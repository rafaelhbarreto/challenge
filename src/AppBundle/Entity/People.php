<?php

namespace AppBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="people")
 */
class People
{

  public function __construct() 
  {
    $this->phones = new ArrayCollection(); 
    $this->orders = new ArrayCollection(); 
  }

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100, nullable=false)
     */
    private $person_name;    

    /**
     * @ORM\OneToMany(targetEntity="Phone", mappedBy="person")
     */
    private $phones; 
    
    /**
     * @ORM\OneToMany(targetEntity="Order", mappedBy="person")
     */
    private $orders; 

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
     * Set person_name
     *
     * @param string $personName
     * @return People
     */
    public function setPersonName($personName)
    {
        $this->person_name = $personName;

        return $this;
    }

    /**
     * Get person_name
     *
     * @return string 
     */
    public function getPersonName()
    {
        return $this->person_name;
    }

    /**
     * Add phones
     *
     * @param \AppBundle\Entity\Phone $phones
     * @return People
     */
    public function addPhone(\AppBundle\Entity\Phone $phones)
    {
        $this->phones[] = $phones;

        return $this;
    }

    /**
     * Remove phones
     *
     * @param \AppBundle\Entity\Phone $phones
     */
    public function removePhone(\AppBundle\Entity\Phone $phones)
    {
        $this->phones->removeElement($phones);
    }

    /**
     * Get phones
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPhones()
    {
        return $this->phones;
    }

    /**
     * Add orders
     *
     * @param \AppBundle\Entity\Order $orders
     * @return People
     */
    public function addOrder(\AppBundle\Entity\Order $orders)
    {
        $this->orders[] = $orders;

        return $this;
    }

    /**
     * Remove orders
     *
     * @param \AppBundle\Entity\Order $orders
     */
    public function removeOrder(\AppBundle\Entity\Order $orders)
    {
        $this->orders->removeElement($orders);
    }

    /**
     * Get orders
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getOrders()
    {
        return $this->orders;
    }
}
