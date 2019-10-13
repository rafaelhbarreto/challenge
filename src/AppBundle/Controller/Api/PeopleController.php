<?php

namespace AppBundle\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class PeopleController extends Controller
{
    // single person 
    public function getPersonAction($id) {

        $person = $this->get('app.people_repository')->getPerson($id); 

        if (!$person) {
            throw $this->createNotFoundException('Person not found.');
        }

        return new JsonResponse([
            'id' => $person->getId(),
            'name' => $person->getPersonName()
        ]);
    }

    // colection of person
    public function getPeopleAction() {
        
        $people = [];

        $resultPeople = $this->get('app.people_repository')->getPeople(); 
        
        if(count($resultPeople) > 0 ){
            foreach($resultPeople as $person) {
                $aux = ['id' => $person->getId(), 'name' => $person->getPersonName()];
                array_push($people, $aux);
            }
        }
        
        return new JsonResponse($people);
    }
    
    
    public function getPeoplePhonesAction($id) {
        $person = $this->get('app.people_repository')->getPerson($id); 

        if (!$person) {
            throw $this->createNotFoundException('Person not found.');
        }

        $phones = []; 
        if($person->getPhones()->count() > 0 ) {
            foreach($person->getPhones() as $phone) {
                 array_push($phones, ['id' => $phone->getId(),'phone' => $phone->getPhone()]);
            }
        }

        return new JsonResponse($phones);
    }


    public function getPeopleOrdersAction($id) {

        $person = $this->get('app.people_repository')->getPerson($id); 

        if (!$person) {
            throw $this->createNotFoundException('Person not found.');
        }

        $orders = []; 
        if($person->getOrders()->count() > 0 ) {
            foreach($person->getOrders() as $order) {

                // get the ship information
                $ship = []; 
                $ship['id'] = $order->getShip()->getId();
                $ship['name'] = $order->getShip()->getName();
                $ship['address'] = $order->getShip()->getAddress();
                $ship['city'] = $order->getShip()->getCity();
                $ship['country'] = $order->getShip()->getCountry();
                $orders['ship'][] = $ship; 
                
                // get the items of order 
                $items = []; 
                if($order->getItems()->count() > 0 ) {
                    foreach($order->getItems() as $item) {
                        $orders['items'][] = [
                            'id' => $item->getId(),
                            'title' => $item->getTitle(),
                            'note' => $item->getNote(),
                            'quantity' => $item->getQuantity(),
                            'price' => $item->getPrice(),
                        ];
                    }
                }
            }
        }

        return new JsonResponse($orders);
    }
}