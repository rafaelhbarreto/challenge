<?php

namespace AppBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="order")
 */
class Order
{
    public function __construct() 
    {
      $this->items = new ArrayCollection(); 
    }

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="People", inversedBy="orders", cascade={"persist"})
     * @ORM\JoinColumn(name="person_id", referencedColumnName="id")
     */
    private $person;

    /**
     * @ORM\OneToOne(targetEntity="Ship", mappedBy="order")
     */
    private $ship; 

    /**
     * @ORM\OneToMany(targetEntity="Item", mappedBy="order")
     */
    private $items; 

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
     * Set person
     *
     * @param \AppBundle\Entity\People $person
     * @return Order
     */
    public function setPerson(\AppBundle\Entity\People $person = null)
    {
        $this->person = $person;

        return $this;
    }

    /**
     * Get person
     *
     * @return \AppBundle\Entity\People 
     */
    public function getPerson()
    {
        return $this->person;
    }

    /**
     * Set ship
     *
     * @param \AppBundle\Entity\Ship $ship
     * @return Order
     */
    public function setShip(\AppBundle\Entity\Ship $ship = null)
    {
        $this->ship = $ship;

        return $this;
    }

    /**
     * Get ship
     *
     * @return \AppBundle\Entity\Ship 
     */
    public function getShip()
    {
        return $this->ship;
    }

    /**
     * Add items
     *
     * @param \AppBundle\Entity\Item $items
     * @return Order
     */
    public function addItem(\AppBundle\Entity\Item $items)
    {
        $this->items[] = $items;

        return $this;
    }

    /**
     * Remove items
     *
     * @param \AppBundle\Entity\Item $items
     */
    public function removeItem(\AppBundle\Entity\Item $items)
    {
        $this->items->removeElement($items);
    }

    /**
     * Get items
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getItems()
    {
        return $this->items;
    }
}
