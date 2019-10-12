<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;

use AppBundle\Service\PeopleStorage; 
use AppBundle\Service\OrderStorage; 
use AppBundle\Helper\Helper; 

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
    public function indexAction()
    {
        // renders app/Resources/views/upload/number.html.twig
        return $this->render('upload/form.html.twig');
    }

    /**
     * @Route("/process")
     * @Template()
     * @throws \Exception
     * 
     * Process the uploaded file and save all data on database
     */
    public function proccessUploadAction(Request $request)
    {
        if ($request->getMethod() == 'POST') {

            $em = $this->getDoctrine()->getManager();
            $peopleFile = $request->files->get('peopleFile');
            $ordersFile = $request->files->get('ordersFile');
            $rootDir = $this->container->getParameter('kernel.root_dir'); 

            try 
            {
                // upload the people file
                $peopleFilename = Helper::uploadFile($peopleFile, $rootDir); 
                
                // storage the content on database 
                $peopleStorage = new PeopleStorage($em); 
                $peopleStorage->storage($peopleFilename);
                
                // upload the orders file
                $ordersFilename = Helper::uploadFile($ordersFile, $rootDir); 

                // storage the content on database 
                $ordersStorage = new OrderStorage($em); 
                $ordersStorage->storage($ordersFilename);
                
                return $this->render('upload/form.html.twig', array(
                    'msg' => 'Success!'
                ));
                
            }
            catch(\Exception $e) {
                die($e->getMessage()); 
            }
        }
    }
}
