<?php

namespace AppBundle\Service;

use AppBundle\Entity\Order;
use AppBundle\Entity\Ship;
use AppBundle\Entity\Item;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class OrderStorage
{
    protected $entityManager; 

    public function __construct(Entitymanager $em) 
    {
        $this->entityManager = $em; 
    }

    public function storage(UploadedFile $file) 
    { 
        if(!$file->isValid()) {
            throw new \Exception('Invalid File'); 
        }

        $loadXml = json_decode(json_encode(simplexml_load_file($file->getRealPath())));

        if(empty($loadXml)){
            throw new \Exception('Empty File'); 
        }
        
        if(count($loadXml->shiporder) > 0 ) {
            
            foreach($loadXml->shiporder as $shiporder) {
                
                
                $person = $this->entityManager
                ->getRepository('AppBundle:People')
                ->find((int)$shiporder->orderperson);
                
                // insert the new person case not exist. 
                if(!$person) {
                    // exception
                }
                
                $order = new Order();
                $order->setId((int)$shiporder->orderid);
                $order->setPerson($person);
                $this->entityManager->persist($order);
                
                if(!empty($shiporder->shipto)) {
                    $shipto = new Ship();
                    $shipto->setName($shiporder->shipto->name);
                    $shipto->setAddress($shiporder->shipto->address);
                    $shipto->setCity($shiporder->shipto->city);
                    $shipto->setCountry($shiporder->shipto->country);
                    $shipto->setOrder($order);
                    $this->entityManager->persist($shipto); 
                }
                
                if (!empty($shiporder->items)) {
                    
                    foreach ($shiporder->items as $item) {
                        
                        $item = is_array($item) ? $item : [$item];

                        foreach($item as $it) {
                            $newItem = new Item();
                            $newItem->setTitle($it->title);
                            $newItem->setNote($it->note);
                            $newItem->setQuantity($it->quantity);
                            $newItem->setPrice($it->price);
                            $newItem->setOrder($order);
                            $this->entityManager->persist($newItem); 
                        }
                    }
                }
            }
        }

        $this->entityManager->flush();

    }

}