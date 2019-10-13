<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Symfony\Component\HttpFoundation\Request;

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
        ->add('shipordersFile', FileType::class)
        ->add('Realizar upload', SubmitType::class, array(
            'attr' => array('class' => 'btn btn-primary'),
        ))->getForm();
        $form->handleRequest($request); 

        if($form->isSubmitted()) {

            try 
            {
                $formData = $form->getData(); 

                $peopleFile = $formData['peopleFile'];
                $ordersFile = $formData['shipordersFile'];

                // calling by dependences injection
                // se more in https://symfony.com/doc/2.8/service_container.html

                $this->container->get('app.people_storage')->storage($peopleFile);
                $this->container->get('app.orders_storage')->storage($ordersFile);

                $msg = [
                    'text' => 'Arquivos processados com sucesso!',
                    'type' => 'success' 
                ]; 
            }
            catch(\Exception $e) {
                $msg = [
                    'text' => $e->getMessage(),
                    'type' => 'danger' 
                ]; 
            }
            finally {

                $this->get('session')->getFlashBag()->add($msg['type'], $msg['text']);
            }
        }

        return $this->render('upload/form.html.twig', array(
            'uploadForm' => $form->createView(),
        ));
    }
}
