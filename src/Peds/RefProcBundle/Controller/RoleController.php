<?php

namespace Peds\RefProcBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Peds\RefProcBundle\Form\RoleType;
use Peds\EntitiesBundle\Entity\Role;

class RoleController extends Controller
{
    public function indexAction()
    {
		$user = $this->get('security.context')->getToken()->getUser();
        $form = $this->createForm(new RoleType(),null,array(
		'user_id' => $user->getId(),
		'translator_service' =>$this->get('translator')
		));
        return $this->render('PedsRefProcBundle:Default:role.html.twig', array('form' => $form->createView(),));
    }

    public function removeAction(Request $request)
    {
        $defaultData = array('');
        $form = $this->createFormBuilder($defaultData)
        ->add('roles', 'entity', array(
        'class' => 'PedsEntitiesBundle:Role',))
        ->getForm();

        if ($request->isMethod('POST')) {
            $form->bind($request);
            $em = $this->getDoctrine()->getEntityManager();
            // data is an array with "name", "email", and "message" keys
            $data = $form->getData();
            $role_to_be_removed=$data['roles'];
            //print_r($data);
            $role_name=$role_to_be_removed->getShortName();
            $em->remove($role_to_be_removed);
            $em->flush();
            $this->get('session')->getFlashBag()->add('notice', 'Role '.$role_name.' was deleted successfully!');
			$url = $this->getRequest()->headers->get("referer");
			return $this->redirect($url);
        }

        return $this->render('PedsRefProcBundle:Default:role_remove.html.twig', array('form' => $form->createView(),));
    }
	public function editListAction(Request $request)
    {
        $defaultData = array('');
        $form = $this->createFormBuilder($defaultData)
        ->add('roles', 'entity', array(
        'class' => 'PedsEntitiesBundle:Role',))
        ->getForm();

        return $this->render('PedsRefProcBundle:Default:role_edit_list.html.twig', array('form' => $form->createView(),));
    }
	public function editListAuxAction(Request $request)
    {
		if ($request->isMethod('POST')) {
			$role_to_be_edited=$_POST['form']['roles'];
		}

		return $this->redirect($this->generateUrl('peds_edit_role',array('id' => $role_to_be_edited)), 301);
    }
	public function editAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $defaultData = array('');
        $role = $em->getRepository('PedsEntitiesBundle:Role')->find($id);
		
        if (!$role) {
             throw $this->createNotFoundException(
                'No Role found for id '.$id);
        }
		$user = $this->get('security.context')->getToken()->getUser();
		$form = $this->createForm(new RoleType(), $role,array(
		'user_id' => $user->getId(),
		'translator_service' =>$this->get('translator')
		));

        if ($this->getRequest()->isMethod('POST')) {
        $form->bind($this->getRequest());

            if ($form->isValid()) {
            //print_r($_POST);
            $role = $form->getData();
            $em->persist($role);
            $em->flush();
            $this->get('session')->getFlashBag()->add('notice', 'Role entry updated!');
            

            }//isvalid
        }
        return $this->render('PedsRefProcBundle:Default:role_edit.html.twig',array('form' => $form->createView(),'id' => $id ));
    }

    public function removeIdAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $role = $em->getRepository('PedsEntitiesBundle:Role')->find($id);

        if (!$role) {
             throw $this->createNotFoundException(
                'No Role found for id '.$id);
        }
		$role_name=$role->getShortName();
        $em->remove($role);
        $em->flush();
        $this->get('session')->getFlashBag()->add('notice', 'Role '.$role_name.' was deleted successfully!');
		//$this->get('session')->getFlashBag()->add('notice', 'Role with id='.$id.' was deleted successfully!');
		$url = $this->getRequest()->headers->get("referer");
		return $this->redirect($url);
        //return $this->render('PedsRefProcBundle:Default:role_remove_id.html.twig',array('id' => $id));
    }

    public function newAction()
	{
	$em = $this->getDoctrine()->getEntityManager();
    $role = new Role();
	$user = $this->get('security.context')->getToken()->getUser();
    $form = $this->createForm(new RoleType(), $role,array(
		'user_id' => $user->getId(),
		'translator_service' =>$this->get('translator')
	));
		

if ($this->getRequest()->isMethod('POST')) {
    $form->bind($this->getRequest());

    if ($form->isValid()) {
        $url = $this->getRequest()->headers->get("referer");
       //print_r($_POST);
    	$role = $form->getData();
    	$em->persist($role);
        $em->flush();
    	$this->get('session')->getFlashBag()->add('notice', 'Role created successfully!');
		return $this->redirect($url);

    }//isvalid
}
	return $this->render('PedsRefProcBundle:Default:role.html.twig', array('form' => $form->createView(),));
	}
}
