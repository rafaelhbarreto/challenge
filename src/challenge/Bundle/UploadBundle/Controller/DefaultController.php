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
     * @Route("/process")
     * @Template()
     * 
     * Process the uploaded file and save all data on database
     */
    public function proccessUploadAction(Request $request)
    {
        if ($request->getMethod() == 'POST') {

            // retrieves an instance of UploadedFile identified by file
            $xmlFile = $request->files->get('xmlFile');

            // verify the size and some error
            if (($xmlFile instanceof UploadedFile) && ($xmlFile->getError() == 00)) {

                if (!$xmlFile->getSize() < 100000) {
                    
                    

                    $extension = $xmlFile->guessExtension(); 
                    if ( $extension !== 'xml') { 
                        die('unauthorized'); 
                    } 
                    else { 
                        
                        // tratar o nome do arquivo 
                        // colocar o arquivo na pasta de upload
                        // ler o conteudo do arquivo
                        // gravar o conteudo do arquivo no banco de dados

                        $originalName = $xmlFile->getClientOriginalName();
                        
                        // Generate a unique name for the file before saving
                        $fileName = md5(uniqid()).'.'.$xmlFile->guessExtension();

                        // Move the file to the directory where brochures are stored
                        $uploadsDir = $this->container->getParameter('kernel.root_dir').'/../web/uploads/xml';
                        $xmlFile->move($uploadsDir, $fileName);

                        return $this->render('upload/form.html.twig', array(
                            'msg' => 'Success!'
                        ));

                    }

                } else {
                    die('size excedded');
                }
            }
        } else {
            exit('unauthorized');
        }
    }
}
