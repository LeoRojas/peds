<?php

namespace Peds\RefProcBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Peds\RefProcBundle\Form\TaskType;
use Peds\EntitiesBundle\Entity\Task;
use Peds\EntitiesBundle\Entity\InputProducts;
use Peds\EntitiesBundle\Entity\OutputProducts;

class TaskController extends Controller
{
    public function indexAction()
    {
        //$form = $this->createForm(new TaskType());
		$user = $this->get('security.context')->getToken()->getUser();
		$form = $this->createForm(new TaskType(), null, array(
		'user_id' => $user->getId(),
		'translator_service' =>$this->get('translator')
		));
        return $this->render('PedsRefProcBundle:Default:task.html.twig', array('form' => $form->createView(),));
    }

    public function removeAction(Request $request)
    {
        $defaultData = array('');
        $form = $this->createFormBuilder($defaultData)
        ->add('tasks', 'entity', array(
        'class' => 'PedsEntitiesBundle:Task',))
        ->getForm();

        if ($request->isMethod('POST')) {
            $form->bind($request);
            $em = $this->getDoctrine()->getEntityManager();
            // data is an array with "name", "email", and "message" keys
            $data = $form->getData();
            $task_to_be_removed=$data['tasks'];
            //print_r($data);
            $task_name=$task_to_be_removed->getShortName();
            $em->remove($task_to_be_removed);
            $em->flush();
            $this->get('session')->getFlashBag()->add('notice', 'Activity '.$task_name.' was deleted successfully!');
        }
		$url = $this->getRequest()->headers->get("referer");
		
        //return $this->render('PedsRefProcBundle:Default:task_remove.html.twig', array('form' => $form->createView(),));
		return $this->redirect($url);
    }

    public function removeIdAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $task = $em->getRepository('PedsEntitiesBundle:Task')->find($id);
		
        if (!$task) {
             throw $this->createNotFoundException(
                'No Task found for id '.$id);
        }
		$task_name=$task->getShortName();
        $em->remove($task);
        $em->flush();
        $this->get('session')->getFlashBag()->add('notice', 'Task '.$task_name.' was deleted successfully!');
		
		$url = $this->getRequest()->headers->get("referer");
		return $this->redirect($url);
        //return $this->render('PedsRefProcBundle:Default:task_remove_id.html.twig',array('id' => $id));
    }

    public function editAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $defaultData = array('');
        $task = $em->getRepository('PedsEntitiesBundle:Task')->find($id);
        if (!$task) {
            throw $this->createNotFoundException(
                'No task found for id '.$id
            );
        }
        //$form = $this->createForm(new TaskType(), $task);
		$user = $this->get('security.context')->getToken()->getUser();
		$form = $this->createForm(new TaskType(), $task, array(
		'user_id' => $user->getId(),
		'translator_service' =>$this->get('translator')
		));
        if ($this->getRequest()->isMethod('POST')) {
        $form->bind($this->getRequest());

            if ($form->isValid()) {
            //print_r($_POST);
            $task = $form->getData();
            $em->persist($task);
            $em->flush();
			//Delete old input and output products
			$this->removeProductRows($task,0);
			$this->removeProductRows($task,1);
			//Create input and output products
			if( (isset($_POST['Task']['input_prods'])) && (!empty($_POST['Task']['input_prods'])) ){
				$iprod_array=$_POST['Task']['input_prods'];
				$this->createProductRows($iprod_array,$task->getId(),0);
			}
			if( (isset($_POST['Task']['output_prods'])) && (!empty($_POST['Task']['output_prods'])) ){
				$oprod_array=$_POST['Task']['output_prods'];
				$this->createProductRows($oprod_array,$task->getId(),1);
			}
			//
            $this->get('session')->getFlashBag()->add('notice', 'Task entry updated!');
            

            }//isvalid
        }
		$iprods=$em->getRepository('PedsEntitiesBundle:InputProducts')->findByTask($task);
		$oprods=$em->getRepository('PedsEntitiesBundle:OutputProducts')->findByTask($task);
		$work_prods=array();
		$work_prods[0]=$iprods;
		$work_prods[1]=$oprods;
        return $this->render('PedsRefProcBundle:Default:task_edit.html.twig',array('form' => $form->createView(),'id' => $id,'work_prods' =>$work_prods));
    }

    public function newAction()
	{
	$em = $this->getDoctrine()->getEntityManager();
    $task = new Task();
    //$form = $this->createForm(new TaskType(), $task);
	$user = $this->get('security.context')->getToken()->getUser();
	$form = $this->createForm(new TaskType(), $task, array(
		'user_id' => $user->getId(),
		'translator_service' =>$this->get('translator')
		));

    if ($this->getRequest()->isMethod('POST')) {
        $form->bind($this->getRequest());

        if ($form->isValid()) {
            $url = $this->getRequest()->headers->get("referer");
            print_r($_POST);
            $task = $form->getData();
            $em->persist($task);
            $em->flush();
			//Create input and output products
			if( (isset($_POST['Task']['input_prods'])) && (!empty($_POST['Task']['input_prods'])) ){
				$iprod_array=$_POST['Task']['input_prods'];
				$this->createProductRows($iprod_array,$task->getId(),0);
			}
			if( (isset($_POST['Task']['output_prods'])) && (!empty($_POST['Task']['output_prods'])) ){
				$oprod_array=$_POST['Task']['output_prods'];
				$this->createProductRows($oprod_array,$task->getId(),1);
			}
			//
			$task_name=$task->getShortName();
            $this->get('session')->getFlashBag()->add('notice', 'Task '.$task_name.' created successfully!');
            return $this->redirect($url);

        }//isvalid
    }
        return $this->render('PedsRefProcBundle:Default:task.html.twig', array('form' => $form->createView(),));
	
	}
		//0 to create input products
		//1 to create output products
	public function createProductRows($product_array,$task_id,$ptype)
    {
		$em = $this->getDoctrine()->getEntityManager();
		$task = $em->getRepository('PedsEntitiesBundle:Task')->find($task_id);
		foreach($product_array as $key => $product_id){
				if($ptype==0){
					$auxprod = new InputProducts();
				}
				else{
					$auxprod = new OutputProducts();
				}
				$auxprod->setTask($task);
				$prod=$em->getRepository('PedsEntitiesBundle:Product')->find($product_id);
				$auxprod->setProduct($prod);
				$em->persist($auxprod);
		}
		$em->flush();
	}
	//0 to remove input products
	//1 to remove output products
	public function removeProductRows($task,$ptype){
		$em = $this->getDoctrine()->getEntityManager();
		if($ptype==0){
			$auxprods=$em->getRepository('PedsEntitiesBundle:InputProducts')->findByTask($task);
		}
		else{
			$auxprods=$em->getRepository('PedsEntitiesBundle:OutputProducts')->findByTask($task);
		}
		foreach($auxprods as $key => $prod){
			$em->remove($prod);
		}
		$em->flush();
	}
}
