<?php

namespace AppBundle\Service;
use AppBundle\Entity\People;
use AppBundle\Entity\Phone;
use Doctrine\ORM\EntityManager;

/**
 * @package AppBundle\Service
 */
final class PeopleStorage implements XmlHandlerInterface
{
  /**
   * instance of Entity Manager
   */
  protected $entityManager; 

  public function __construct(EntityManager $em) 
  {
    $this->entityManager = $em; 
  }

  /**
   * Handle the upload
   * 
   * @throws \Exception
   */
  public function storage($filename) 
  { 
    $loadXml = json_decode(json_encode(simplexml_load_file($filename)));
    
    if(count($loadXml->person) > 0 ) {

      foreach($loadXml->person as $person) {

        $people =  new People(); 
        $people->setId($person->personid);
        $people->setPersonName($person->personname);
        $this->entityManager->persist($people);

        if(!empty($person->phones->phone )) {

          $phones = is_array($person->phones->phone) ? $person->phones->phone : [$person->phones->phone];

          foreach($phones as $phone) {

            $newPhone = new Phone(); 
            $newPhone->setPerson($people);
            $newPhone->setPhone($phone);
            $this->entityManager->persist($newPhone);
          }
        }
      }
      $this->entityManager->flush();
    }
  }
}