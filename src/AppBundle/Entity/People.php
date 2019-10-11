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
    private $personName;    

    /**
     * @ORM\OneToMany(targetEntity="Phone", mappedBy="person")
     */
    private $phones; 
}