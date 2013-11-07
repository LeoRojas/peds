<?php

namespace Peds\RefProcBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Peds\RefProcBundle\Form\RPType;
use Peds\EntitiesBundle\Entity\ReferenceProcess;

class RefProcController extends Controller
{
    public function indexAction()
    {
        $form = $this->createForm(new RPType(),null,array(
		'translator_service' => $this->get('translator')
		));
        return $this->render('PedsRefProcBundle:Default:ref_proc.html.twig', array('form' => $form->createView(),));
    }
    
    public function removeAction(Request $request)
    {
    	$defaultData = array('');
    	$form = $this->createFormBuilder($defaultData)
        ->add('rps', 'entity', array(
    	'class' => 'PedsEntitiesBundle:ReferenceProcess',))
        ->getForm();

        if ($request->isMethod('POST')) {
            $form->bind($request);
            $em = $this->getDoctrine()->getEntityManager();
            // data is an array with "name", "email", and "message" keys
            $data = $form->getData();
            $rp_to_be_removed=$data['rps'];
            //print_r($data);
            $rp_name=$rp_to_be_removed->getShortName();
            $em->remove($rp_to_be_removed);
			$em->flush();
            $this->get('session')->getFlashBag()->add('notice', 'RP '.$rp_name.' was deleted successfully!');
        }

    	return $this->render('PedsRefProcBundle:Default:ref_proc_remove.html.twig', array('form' => $form->createView(),));
    }

    public function removeIdAction($id)
    {
    	$em = $this->getDoctrine()->getEntityManager();
    	$rp = $em->getRepository('PedsEntitiesBundle:ReferenceProcess')->find($id);

    	if (!$rp) {
       		 throw $this->createNotFoundException(
            	'No ReferenceProcess found for id '.$id);
    	}
		$rp_name=$rp->getShortName();
		$em->remove($rp);
		$em->flush();

        $this->get('session')->getFlashBag()->add('notice', 'RP '.$rp_name.' was deleted successfully!');
		$url = $this->getRequest()->headers->get("referer");
		return $this->redirect($url);
        //return $this->render('PedsRefProcBundle:Default:ref_proc_remove_id.html.twig',array('id' => $id));
    }
    public function editAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $defaultData = array('');
        $rp = $em->getRepository('PedsEntitiesBundle:ReferenceProcess')->find($id);
        if (!$rp) {
            throw $this->createNotFoundException(
                'No reference process found for id '.$id
            );
        }
        //$matching = new Matching();
        //$form = $this->createForm(new RPType(), $rp);
		$form = $this->createForm(new RPType(),$rp,array(
		'translator_service' => $this->get('translator')
		));

        if ($this->getRequest()->isMethod('POST')) {
        $form->bind($this->getRequest());

            if ($form->isValid()) {
            //print_r($_POST);
            $rp = $form->getData();
            $em->persist($rp);
            $em->flush();
            $this->get('session')->getFlashBag()->add('notice', 'RP entry updated!');
            

            }//isvalid
        }
        return $this->render('PedsRefProcBundle:Default:ref_proc_edit.html.twig',array('form' => $form->createView(),'id' => $id ));
    }
    
    public function newAction()
	{
	$em = $this->getDoctrine()->getEntityManager();
    $rp = new ReferenceProcess();
	//$form = $this->createForm(new RPType(), $rp);
	$form = $this->createForm(new RPType(),$rp,array(
		'translator_service' => $this->get('translator')
		));
		

if ($this->getRequest()->isMethod('POST')) {
    $form->bind($this->getRequest());

    if ($form->isValid()) {
        $url = $this->getRequest()->headers->get("referer");
    	//print_r($_POST);
    	$rp = $form->getData();
		$user = $this->get('security.context')->getToken()->getUser();
		$rp->setOwner($user);
    	$em->persist($rp);
        $em->flush();
		$rp_name=$rp->getShortName();
		
		/*
		$this->get('session')->set('_locale', 'es');
		$request = $this->getRequest();
		$locale = $request->getLocale();
		$request->setLocale('es');
		*/

		$translated = $this->get('translator')->trans(
        'rp.new',
        array('%rp_name%' => $rp_name)
		);

		$this->get('session')->getFlashBag()->add('notice', $translated);
        //$this->get('session')->getFlashBag()->add('notice', 'RP '.$rp_name.' created successfully!');
        //$_POST=array();
        return $this->redirect($url);
        //return $this->redirect($url, 301);
		//$form = $this->createForm(new RPType());
        //return $this->render('PedsRefProcBundle:Default:ref_proc.html.twig', array('form' => $form->createView(),));

    }//isvalid
}
	return $this->render('PedsRefProcBundle:Default:ref_proc.html.twig', array('form' => $form->createView(),));

	}
}
