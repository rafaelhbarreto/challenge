<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;

use AppBundle\Entity\People; 
use AppBundle\Entity\Phone; 

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

                    $entityManager = $this->getDoctrine()->getManager(); 

                    foreach($peopleObject as $person) 
                    {

                        // ??
                        // verificar se a pessoa ja existe no banco de dados ?? 
                        // ?? 

                        $people =  new People(); 
                        $people->setPersonName($person->personname);
                        $entityManager->persist($people);
                        
                        // phones 
                        if(count($person->phones) > 0 ){
                            foreach($person->phones as $item){
                                $newPhone = new Phone(); 
                                $newPhone->setPerson($people);
                                $newPhone->setPhone($item->phone);
                                $entityManager->persist($newPhone);
                            }
                        }
                    }

                    $entityManager->flush();
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
       
        $fileName = '00d8b206500bf1c912b47b24cc381dc7.xml';
        $path = $this->container->getParameter('kernel.root_dir').'/../web/uploads/xml/'.$fileName;
        $xmldata = simplexml_load_file($path);
        var_dump($xmldata);
        return $xmldata;
    }
}
