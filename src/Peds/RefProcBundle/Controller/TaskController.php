<?php

namespace Peds\RefProcBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Peds\RefProcBundle\Form\TaskType;
use Peds\EntitiesBundle\Entity\Task;

class TaskController extends Controller
{
    public function indexAction()
    {
        //$form = $this->createForm(new TaskType());
		$user = $this->get('security.context')->getToken()->getUser();
		$form = $this->createForm(new TaskType(), null, array(
		'user_id' => $user->getId()
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
		'user_id' => $user->getId()
		));
        if ($this->getRequest()->isMethod('POST')) {
        $form->bind($this->getRequest());

            if ($form->isValid()) {
            //print_r($_POST);
            $task = $form->getData();
            $em->persist($task);
            $em->flush();
            $this->get('session')->getFlashBag()->add('notice', 'Task entry updated!');
            

            }//isvalid
        }
        return $this->render('PedsRefProcBundle:Default:task_edit.html.twig',array('form' => $form->createView(),'id' => $id ));
    }

    public function newAction()
	{
	$em = $this->getDoctrine()->getEntityManager();
    $task = new Task();
    //$form = $this->createForm(new TaskType(), $task);
	$user = $this->get('security.context')->getToken()->getUser();
	$form = $this->createForm(new TaskType(), $task, array(
		'user_id' => $user->getId()
		));

    if ($this->getRequest()->isMethod('POST')) {
        $form->bind($this->getRequest());

        if ($form->isValid()) {
            $url = $this->getRequest()->headers->get("referer");
            //print_r($_POST);
            $task = $form->getData();
            $em->persist($task);
            $em->flush();
			$task_name=$task->getShortName();
            $this->get('session')->getFlashBag()->add('notice', 'Task '.$task_name.' created successfully!');
            return $this->redirect($url);

        }//isvalid
    }
        return $this->render('PedsRefProcBundle:Default:task.html.twig', array('form' => $form->createView(),));
	
	}
}
