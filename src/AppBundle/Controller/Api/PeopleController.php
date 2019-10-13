<?php

namespace AppBundle\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;


use AppBundle\Entity\People; 

class PeopleController extends Controller
{
    public function getPersonAction($id) {

        $person = $this->getDoctrine()->getRepository(People::class)->find($id);

        if (!$person) {
            throw $this->createNotFoundException('Person not found.');
        }

        return new JsonResponse(json_decode($this->get('jms_serializer')->serialize($person, 'json')));
    }

    public function getPeopleAction() {
        
        $persons = [];
        
        $resultPersons = $this->getDoctrine()->getRepository(People::class)->findAll();
        
        if(count($resultPersons) > 0 ){
            foreach($resultPersons as $person) {
                $aux = ['id' => $person->getId(), 'name' => $person->getPersonName()];
                array_push($persons, $aux);
            }
        }
        
        return new JsonResponse($persons);
    }
    
    
    /**
     * Route /api/persons/{id}/phones
     * 
     * @param int $id
     * @return json
     */
    public function getPersonsPhonesAction($id) {
        $person = $this->getDoctrine()->getRepository(People::class)->find($id);

        if(!$person) {
            return new JsonResponse(['msg' => 'Person not found!']);
        }

        $phones = []; 
        if($person->getPhones()->count() > 0 ) {
            foreach($person->getPhones() as $phone) {
                 array_push($phones, ['id' => $phone->getId(),'phone' => $phone->getPhone()]);
            }
        }

        return new JsonResponse($phones);
    }


    /**
     * Route /api/person/{person_id}/orders
     * 
     * @param int $id
     * @return json
     */
    public function getPersonsOrdersAction($id) {
        $person = $this->getDoctrine()->getRepository(People::class)->find($id);

        if(!$person) {
            return new JsonResponse(['msg' => 'Person not found!']);
        }

        $orders = []; 
        if($person->getOrders()->count() > 0 ) {
            foreach($person->getOrders() as $order) {

                // get the ship information
                $ship = []; 
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
                            'title' => $item->getTitle(),
                            'note' => $item->getNote(),
                            'quantity' => $item->getQuantity(),
                            'price' => $item->getPrice(),
                        ];
                    }
                }

                 array_push($orders, ['id' => $order->getId()]);
            }
        }

        return new JsonResponse($orders);
    }
}