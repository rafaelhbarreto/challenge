<?php

namespace AppBundle\Service;

use AppBundle\Entity\People;
use AppBundle\Entity\Phone;
use AppBundle\Entity\Order;
use AppBundle\Entity\Ship;
use AppBundle\Entity\item;

use Doctrine\ORM\EntityManager;

final class OrderStorage implements XmlHandlerInterface
{
    /**
     * instance of Entity Manager
     */
    protected $entityManager; 

    public function __construct(EntityManager $em) 
    {
        $this->entityManager = $em; 
    }

    public function storage($filename) 
    { 
        $loadXml = json_decode(json_encode(simplexml_load_file($filename)));

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
                    $person = new People();
                    $person->setId((int)$shiporder->orderperson); 
                    $person->setPersonName('');
                    $this->entityManager->persist($person); 
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
                
                if (!empty($shiporder->items->item)) {
                    foreach ($shiporder->items as $item) {
                        
                        if(!is_array($item)) {
                            $newItem = new Item();
                            $newItem->setTitle($item->title);
                            $newItem->setNote($item->note);
                            $newItem->setQuantity($item->quantity);
                            $newItem->setPrice($item->price);
                            $newItem->setOrder($order);
                            $this->entityManager->persist($newItem); 
                        } else {
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
        }

        $this->entityManager->flush();

    }

}