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
        $form = $this->createForm(new RoleType());
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
            $rol_to_be_removed=$data['roles'];
            //print_r($data);
            $role_name=$rol_to_be_removed->getShortName();
            $em->remove($rol_to_be_removed);
            $em->flush();
            $this->get('session')->getFlashBag()->add('notice', 'Role '.$role_name.' was deleted successfully!');
        }

        return $this->render('PedsRefProcBundle:Default:role_remove.html.twig', array('form' => $form->createView(),));
    }

    public function removeIdAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $rol = $em->getRepository('PedsEntitiesBundle:Role')->find($id);

        if (!$rol) {
             throw $this->createNotFoundException(
                'No Role found for id '.$id);
        }
        $em->remove($rol);
        $em->flush();
        $this->get('session')->getFlashBag()->add('notice', 'Role with id='.$id.' was deleted successfully!');
        return $this->render('PedsRefProcBundle:Default:role_remove_id.html.twig',array('id' => $id));
    }

    public function newAction()
	{
	$em = $this->getDoctrine()->getEntityManager();
    $role = new Role();
    $form = $this->createForm(new RoleType(), $role);
		

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
