<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Symfony\Component\HttpFoundation\Request;

use AppBundle\Helper\HelperUploadFile; 
use AppBundle\Service\PeopleStorage; 
use AppBundle\Service\OrderStorage; 


class DefaultController extends Controller
{
    /**
     * @Route("/")
     * @Template()
     * 
     * Form apresentation
     */
    public function indexAction(Request $request)
    {
        // https://symfony.com/doc/2.8/forms.html

        $form = $this->createFormBuilder()
        ->add('peopleFile', FileType::class)
        ->add('ordersFile', FileType::class)
        ->add('upload', SubmitType::class, array(
            'attr' => array('class' => 'btn btn-primary'),
        ))->getForm();
        $form->handleRequest($request); 

        if($form->isSubmitted()) {

            try 
            {
                $formData = $form->getData(); 

                $peopleFile = $formData['peopleFile'];
                $ordersFile = $formData['ordersFile'];

                // calling by dependences injection
                // se more in https://symfony.com/doc/2.8/service_container.html

                $this->container->get('app.people_storage')->storage($peopleFile);
                $this->container->get('app.orders_storage')->storage($ordersFile);
            }
            catch(\Exeption $e) {
                echo $e->getMessage();
            }
        }

        return $this->render('upload/form.html.twig', array(
            'uploadForm' => $form->createView(),
        ));
    }
}
