<?php

namespace Peds\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Peds\UserBundle\Form\UserType;
use Peds\EntitiesBundle\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RegisterController extends Controller
{
    public function indexAction()
    {
        return $this->render('PedsUserBundle:Default:register.html.twig');
    }
    public function newAction()
	{
	$em = $this->getDoctrine()->getEntityManager();
    $user = new User();
    $form = $this->createForm(new UserType(), $user);

if ($this->getRequest()->isMethod('POST')) {
	//print_r("antes del bind");
    $form->bind($this->getRequest());
    //print_r("antes del form isvalid");
    //print_r($_POST);
    if ($form->isValid()) {
    	//print_r("Hola");
    	//print_r($_POST);
        //$user = $form->getData();

    	//Agregar un if para cuando el user_name esta repetido y redireccionar al registro

        $user->setSalt(sha1(time()));
        //print_r($_POST['user']['role']);
        //print_r($_POST['role']);
        
		//$role = $em->getRepository('PedsEntitiesBundle:UserRole')->find($_POST['user']['role']);
		$role = $em->getRepository('PedsEntitiesBundle:UserRole')->find(2);
        if (!$role) {
        	throw $this->createNotFoundException(
            'No role found for id '.$_POST['user']['role']
        	);
        }
        $user->setRole($role);
        if($_POST['user']['Password']['password'] != null){
	        $factory = $this->get('security.encoder_factory');
	        $encoder = $factory->getEncoder($user);
	        $newpass = $encoder->encodePassword($_POST['user']['Password']['password'],$user->getSalt());
	        $user->setPassword($newpass);
	        //$user->setPassword($_POST['user']['Password']['password']);
	    }
	    $user->setRDate(new \DateTime("now")); 
        $em->persist($user);
        $em->flush();

        //return $this->redirect(...);
        //$this->get('session')->setFlash('notice', 'Depósito Realizado!');
		$this->get('session')->getFlashBag()->add('notice', 'Registration was successful');
		return $this->render('PedsAccountBundle:Security:login.html.twig');
        //return $this->render('PedsUserBundle:Default:register.html.twig', array('form' => $form->createView(),));
		

    }//isvalid
}
    //print_r("Chao");

	return $this->render('PedsUserBundle:Default:register.html.twig', array(
            'form' => $form->createView(),
        ));
    // ...
	}
}
