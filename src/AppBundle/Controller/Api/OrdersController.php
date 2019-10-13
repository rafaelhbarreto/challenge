<?php

namespace AppBundle\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

use AppBundle\Entity\Order; 

class OrdersController extends Controller
{

    /**
     * Route /api/persons/{id}
     */
    public function getOrderAction($id) {

        $order = $this->getDoctrine()->getRepository(Order::class)->find($id);

        if (!$order) {
            throw $this->createNotFoundException('Order not found.');
        }

/*        if(!$order) {
            return new JsonResponse([]);
        }
        */

        return new JsonResponse(json_decode($this->get('jms_serializer')->serialize($order, 'json')));
    }

    public function getOrdersAction() {
        $orders = $this->getDoctrine()->getRepository(Order::class)->findAll();

        $result = [];
        if(count($orders) > 0 ) {
            foreach($orders as $order) {
                $result[] = [
                    'id' => $order->getId(),
                    'person_id' => $order->getPerson()->getId()
                ];
            } 
        }

        return new JsonResponse($result);
    }



    /**
     * Route /api/orders/{id}/ship
     */
    public function getOrdersShipAction($id) {
        $order = $this->getDoctrine()->getRepository(Order::class)->find($id);

        if(!$order) {
            return new JsonResponse([]);
        }

        $ship = [];
        $ship['name'] = $order->getShip()->getName();
        $ship['address'] = $order->getShip()->getAddress();
        $ship['city'] = $order->getShip()->getCity();
        $ship['country'] = $order->getShip()->getCountry();

        return new JsonResponse($ship);
    }

    /**
     * Route /api/orders/{id}/items
     */
    public function getOrdersItemsAction($id) {
        $order = $this->getDoctrine()->getRepository(Order::class)->find($id);

        if(!$order) {
            return new JsonResponse([]);
        }

        $items = [];
        if($order->getItems()->count() > 0 ) {
            foreach($order->getItems() as $item) {
                $aux = [];
                $aux['title'] = $item->getTitle();
                $aux['note'] = $item->getNote();
                $aux['quantity'] = $item->getQuantity();
                $aux['price'] = $item->getPrice();
    
                array_push($items, $aux); 
            }
        }

        return new JsonResponse($items); 
    }

}