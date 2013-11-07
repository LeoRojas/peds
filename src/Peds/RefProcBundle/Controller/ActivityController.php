<?php

namespace Peds\RefProcBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Peds\RefProcBundle\Form\ActivityType;
use Peds\EntitiesBundle\Entity\Activity;

class ActivityController extends Controller
{
    public function indexAction()
    {
        //$form = $this->createForm(new ActivityType());
		$user = $this->get('security.context')->getToken()->getUser();
		$em = $this->getDoctrine()->getEntityManager();
		$rps=$em->getRepository('PedsEntitiesBundle:ReferenceProcess')->findByOwner($user);
		$form = $this->createForm(new ActivityType(), null, array(
		'user_id' => $user->getId(),
		'translator_service' =>$this->get('translator')
		));
		
        return $this->render('PedsRefProcBundle:Default:activity.html.twig', array('form' => $form->createView(),'rps'=>$rps));
    }

    public function removeAction(Request $request)
    {
        $defaultData = array('');
        $form = $this->createFormBuilder($defaultData)
        ->add('activities', 'entity', array(
        'class' => 'PedsEntitiesBundle:Activity',))
        ->getForm();

        if ($request->isMethod('POST')) {
            $form->bind($request);
            $em = $this->getDoctrine()->getEntityManager();
            // data is an array with "name", "email", and "message" keys
            $data = $form->getData();
            $act_to_be_removed=$data['activities'];
            //print_r($data);
            $act_name=$act_to_be_removed->getShortName();
            $em->remove($act_to_be_removed);
            $em->flush();
            $this->get('session')->getFlashBag()->add('notice', 'Activity '.$act_name.' was deleted successfully!');
        }

        return $this->render('PedsRefProcBundle:Default:activity_remove.html.twig', array('form' => $form->createView(),));
    }

    public function removeIdAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $act = $em->getRepository('PedsEntitiesBundle:Activity')->find($id);

        if (!$act) {
             throw $this->createNotFoundException(
                'No Activity found for id '.$id);
        }
        $em->remove($act);
        $em->flush();
		$act_name=$act->getShortName();
        $this->get('session')->getFlashBag()->add('notice', 'Activity '.$act_name.' was deleted successfully!');
		$url = $this->getRequest()->headers->get("referer");
		return $this->redirect($url);
        //return $this->render('PedsRefProcBundle:Default:activity_remove_id.html.twig',array('id' => $id));
    }

    public function editAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $defaultData = array('');
        $act = $em->getRepository('PedsEntitiesBundle:Activity')->find($id);
        if (!$act) {
            throw $this->createNotFoundException(
                'No activity found for id '.$id
            );
        }
        //$form = $this->createForm(new ActivityType(), $act);
		$user = $this->get('security.context')->getToken()->getUser();
		$rps=$em->getRepository('PedsEntitiesBundle:ReferenceProcess')->findByOwner($user);
		$form = $this->createForm(new ActivityType(), $act, array(
		'user_id' => $user->getId(),
		'translator_service' =>$this->get('translator')
		));
        if ($this->getRequest()->isMethod('POST')) {
        $form->bind($this->getRequest());

            if ($form->isValid()) {
            //print_r($_POST);
            $act = $form->getData();
            $em->persist($act);
            $em->flush();
            $this->get('session')->getFlashBag()->add('notice', 'Activity entry updated!');
            

            }//isvalid
        }
        return $this->render('PedsRefProcBundle:Default:activity_edit.html.twig',array('form' => $form->createView(),'id' => $id, 'rps' =>$rps));
    }
    public function newAction()
	{
	$em = $this->getDoctrine()->getEntityManager();
    $activity = new Activity();
	$user = $this->get('security.context')->getToken()->getUser();
	$rps=$em->getRepository('PedsEntitiesBundle:ReferenceProcess')->findByOwner($user);
    $form = $this->createForm(new ActivityType(), $activity, array(
		'user_id' => $user->getId(),
		'translator_service' =>$this->get('translator')
		));
		

if ($this->getRequest()->isMethod('POST')) {
    $form->bind($this->getRequest());

    if ($form->isValid()) {
        $url = $this->getRequest()->headers->get("referer");
       //print_r($_POST);
    	$activity = $form->getData();
    	$em->persist($activity);
        $em->flush();
		$act_name=$activity->getShortName();
        $this->get('session')->getFlashBag()->add('notice', 'Activity '.$act_name.' created successfully!');
        return $this->redirect($url);
		

    }//isvalid
}
	return $this->render('PedsRefProcBundle:Default:activity.html.twig', array('form' => $form->createView(),'rps' =>$rps));
	}
}
