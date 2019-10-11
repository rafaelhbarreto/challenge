<?php

namespace challenge\Bundle\UploadBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;

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
        $returnData['fileName'] = ''; 

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
                    $returnData['fileName'] = $fileName; 
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
