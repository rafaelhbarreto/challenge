<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;

use AppBundle\Entity\People; 
use AppBundle\Entity\Phone; 
use AppBundle\Entity\Order; 
use AppBundle\Entity\Ship; 
use AppBundle\Entity\Item; 

class DefaultController extends Controller
{

    /**
     * @Route("/")
     * @Template()
     * 
     * Form apresentation
     */
    public function uploadAction()
    {
        // renders app/Resources/views/upload/number.html.twig
        return $this->render('upload/form.html.twig');
    }

    /**
     * Recieves a instance of UploadedFile and try upload it.active
     * 
     * The function returns an array with status and mesage of upload.
     * 
     * @param UploadedFile $file
     * @return array  $returnData
     */
    protected function uploadFile(UploadedFile $file) {
        
        $returnData['valid'] = false; 
        $returnData['mesage'] = 'upload fails';
        $returnData['filename'] = ''; 

        if(($file instanceof UploadedFile) && $file->getError() == 0) {
            if(!$file->getSize() < 100000) {
                $extension = $file->guessExtension(); 

                if($extension !== 'xml') {
                    $returnData['mesage'] = 'The given file isen\'t XML';  
                }
                else {
                        
                    // Generate a unique name for the file before saving
                    $fileName = md5(uniqid()).'.'.$file->guessExtension();

                    // Move the file to the directory where brochures are stored
                    $uploadsDir = $this->container->getParameter('kernel.root_dir').'/../web/uploads/xml';
                    $file->move($uploadsDir, $fileName);

                    $returnData['valid'] = true;
                    $returnData['mesage'] = 'the file has uploaded';
                    $returnData['filename'] = $fileName; 
                }
            }
            else {
                $returnData['mesage'] = 'The file size is too big'; 
            }
        }
        else {
            $returnData['mesage'] = 'An error occurren in upload file'; 
        }

        return $returnData; 
    }

    /**
     * Read and load the XML Data and returns the content
     * 
     * @param string $fileName
     * @return object
     */
    protected function loadXml($fileName) {
        $path = $this->container->getParameter('kernel.root_dir').'/../web/uploads/xml/'.$fileName;
        $xmldata = simplexml_load_file($path);
        return $xmldata; 
    }

    /**
     * @Route("/process")
     * @Template()
     * 
     * Process the uploaded file and save all data on database
     */
    public function proccessUploadAction(Request $request)
    {
        if ($request->getMethod() == 'POST') {

            $em = $this->getDoctrine()->getManager();
            $peopleFile = $request->files->get('peopleFile');
            $orders = $request->files->get('ordersFile');

            // prepare the people file to upload and database storage
            $dataUploadPeople = $this->uploadFile($peopleFile); 
            $dataUploadOrders = $this->uploadFile($orders); 

            if(!$dataUploadPeople['valid'] || !$dataUploadOrders['valid']) {
                var_dump('error');   
            }
            else {

                // load the xml People file
                $peopleObject = $this->loadXml($dataUploadPeople['filename']);
                
                //
                // storage the content on the database
                //
                if(count($peopleObject) > 0 ) {

                    foreach($peopleObject as $person) 
                    {

                        // ??
                        // verificar se a pessoa ja existe no banco de dados ?? 
                        // ?? 

                        $people =  new People(); 
                        $people->setId($person->personid);
                        $people->setPersonName($person->personname);
                        $em->persist($people);
                        
                        // phones 
                        if(count($person->phones) > 0 ){
                            foreach($person->phones as $item){
                                $newPhone = new Phone(); 
                                $newPhone->setPerson($people);
                                $newPhone->setPhone($item->phone);
                                $em->persist($newPhone);
                            }
                        }
                    }

                    $em->flush();
                }
                
                // load the xml People file
                $ordersObject = $this->loadXml($dataUploadOrders['filename']);
                
                //
                // storage the content on the database
                //

                if(count($ordersObject) > 0 ) {
                    foreach($ordersObject as $order) {

                        $person = $this->getDoctrine()->getRepository('AppBundle:People')->find($order->orderperson[0]);

                        $orderid = (Int) $order->orderid[0]; 
                        $newOrder = new Order();
                        $newOrder->setId($orderid);
                        $newOrder->setPerson($person);
                        $em->persist($newOrder); 
        
                        if(count($order->shipto) > 0 ) {
                            foreach($order->shipto as $ship) {
                                
                                $newShip = new Ship(); 
                                $newShip->setOrder($newOrder);
                                $newShip->setName($ship->name);
                                $newShip->setAddress($ship->address);
                                $newShip->setCity($ship->city);
                                $newShip->setCountry($ship->country);
                                $em->persist($newShip); 
                            }
                        }
        
                        if(count($order->items) > 0 ) {
                            foreach($order->items as $items) {
                                $newItem = new Item(); 
                                $newItem->setOrder($newOrder);
                                $newItem->setTitle($items->item->title);
                                $newItem->setNote($items->item->note);
                                $newItem->setQuantity($items->item->quantity);
                                $newItem->setPrice($items->item->price);
                                $em->persist($newItem); 
                            }
                        }
                    }

                    $em->flush();
                }

                return $this->render('upload/form.html.twig', array(
                    'msg' => 'Success!'
                ));
            }
        }
    }

    /**
     * @Route("/test")
     * @Template()
     * 
     * Process the uploaded file and save all data on database
     */
    public function test() {
       
    }
}
