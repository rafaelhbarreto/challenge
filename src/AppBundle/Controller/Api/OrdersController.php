<?php

namespace AppBundle\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class OrdersController extends Controller
{

    public function getOrderAction($id) {

        $order = $this->get('app.orders_repository')->getOrder($id);

        if (!$order) {
            throw $this->createNotFoundException('Order not found.');
        }

        $result = [
            'person_id' => $order->getPerson()->getId(),
            'ship' => [
                'name'  => $order->getShip()->getName(),
                'address' => $order->getShip()->getAddress(),
                'city' => $order->getShip()->getCity(),
                'country' => $order->getShip()->getCountry()
            ],
            'items' => []
        ]; 

        if( $order->getItems()->count() > 0 ) {
            foreach($order->getItems() as $item) {
                $result['items'][] = [
                    'id' => $item->getId(),
                    'title' => $item->getTitle(),
                    'note' => $item->getNote(),
                    'quantity' => $item->getQuantity(),
                    'price' => $item->getPrice(),
                ];
            }
        }

        return new JsonResponse($result);
    }

    public function getOrdersAction() {
        $orders = $this->get('app.orders_repository')->getOrders();

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

    public function getOrdersShipAction($id) {

        $order = $this->get('app.orders_repository')->getOrder($id);

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

    public function getOrdersItemsAction($id) {

        $order = $this->get('app.orders_repository')->getOrder($id);

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