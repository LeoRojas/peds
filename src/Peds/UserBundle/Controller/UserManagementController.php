<?php

namespace Peds\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class UserManagementController extends Controller
{
    public function indexAction()
    {
        return $this->render('PedsUserBundle:Default:index.html.twig');
    }
	
	public function editMailAction()
    {
		if ($this->getRequest()->isMethod('POST')) {
		$user = $this->get('security.context')->getToken()->getUser();
		//print_r($_POST);
		$factory = $this->get('security.encoder_factory');
	    $encoder = $factory->getEncoder($user);
	    $en_pass = $encoder->encodePassword($_POST['_password'],$user->getSalt());
	    //$user->setPassword($newpass);
		
			if($en_pass==$user->getPassword()){
				//print_r("ENTERED IF PASS");

				$em = $this->getDoctrine()->getEntityManager();
				$user->setEmail($_POST['_nmail']);
				$em->persist($user);
				$em->flush();
				$this->get('session')->getFlashBag()->add('notice', 'E-mail updated successfully!');
			}
			else{
				$this->get('session')->getFlashBag()->add('error', 'Incorrect password!');
			}
		
		
		return $this->render('PedsUserBundle:Default:edit_mail.html.twig');
		}
        return $this->render('PedsUserBundle:Default:edit_mail.html.twig');
    }
	
	public function editPassAction()
	{
		if ($this->getRequest()->isMethod('POST')) {
			$user = $this->get('security.context')->getToken()->getUser();
			//print_r($_POST);
			$factory = $this->get('security.encoder_factory');
			$encoder = $factory->getEncoder($user);
			$en_pass = $encoder->encodePassword($_POST['_cpassword'],$user->getSalt());
			//$user->setPassword($newpass);
		
			if($en_pass==$user->getPassword()){
				//print_r("ENTERED IF PASS");
				if($_POST['_npassword']==$_POST['_rnpassword']){
					//print_r("ENTERED IF REPEAT");
					$em = $this->getDoctrine()->getEntityManager();
					//$user->setEmail($_POST['_nmail']);
					$newpass = $encoder->encodePassword($_POST['_npassword'],$user->getSalt());
					$user->setPassword($newpass);
					$em->persist($user);
					$em->flush();
					$this->get('session')->getFlashBag()->add('notice', 'Password updated successfully!');
				}
				else{
					$this->get('session')->getFlashBag()->add('error', 'Confirm new password failed!');
				}

			}
			else{
				$this->get('session')->getFlashBag()->add('error', 'Incorrect password!');
			}
			return $this->render('PedsUserBundle:Default:edit_pass.html.twig');
		}
        return $this->render('PedsUserBundle:Default:edit_pass.html.twig');
    }
}
