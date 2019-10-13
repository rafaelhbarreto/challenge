<?php 

// https://symfony.com/doc/2.8/doctrine/repository.html

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManager;
use AppBundle\Entity\People; 

class PeopleRepository extends EntityRepository {

    private $em; 

    public function __construct(EntityManager $em) {
        $this->em = $em; 
    }

    public function getPeople() {
        return $this->em->getRepository(People::class)->findAll();
    }

    public function getPerson($id) {
        return $this->em->getRepository(People::class)->find($id);
    }
}