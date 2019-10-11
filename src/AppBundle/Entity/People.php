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
  }

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $person_name;    

    /**
     * @ORM\OneToMany(targetEntity="Phone", mappedBy="person")
     */
    private $phones; 

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
}
