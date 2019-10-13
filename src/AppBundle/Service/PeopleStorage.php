<?php

namespace AppBundle\Service;

use AppBundle\Entity\People;
use AppBundle\Entity\Phone;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class PeopleStorage
{
  protected $entityManager; 

  public function __construct(EntityManager $em) 
  {
    $this->entityManager = $em; 
  }

  public function storage(UploadedFile $file) 
  { 
    if(!$file->isValid()) {
      throw new \Exception('Invalid File'); 
    }

    $loadXml = json_decode(json_encode(simplexml_load_file($file->getRealPath())));
    
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