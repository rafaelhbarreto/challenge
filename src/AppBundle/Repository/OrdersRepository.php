<?php 

// https://symfony.com/doc/2.8/doctrine/repository.html

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManager;
use AppBundle\Entity\Order; 

class OrdersRepository extends EntityRepository {

    private $em; 

    public function __construct(EntityManager $em) {
        $this->em = $em; 
    }

    public function getOrders() {
        return $this->em->getRepository(Order::class)->findAll();
    }

    public function getOrder($id) {
        return $this->em->getRepository(Order::class)->find($id);
    }
}