<?php

namespace Peds\AccountBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AccountController extends Controller
{
    public function indexAction()
    {
    	$user = $this->get('security.context')->getToken()->getUser();
    	//print_r($user->getId());
    	$em = $this->getDoctrine()->getEntityManager();
    	$user_rps=$em->getRepository('PedsEntitiesBundle:ReferenceProcess')->findByOwner($user->getId());
    	$acts=array();
    	$tasks=array();
    		foreach ($user_rps as $key => $rp) {
    			//array_push($acts, $this->getRp_Acts($rp));
    			//$acts=array_merge($acts, $this->getRp_Acts($rp));
    			$acts[$rp->getId()]=$this->getRp_Acts($rp);
    		}
    		//For debugging purposes
    		//print_r($acts);
    		/*
    		foreach ($acts as $key => $act) {
    			print_r($act->getShortName());
    		}
    		*/

    		
    		foreach ($acts as $key => $act_array) {
    			//array_push($acts, $this->getRp_Acts($rp));
    			foreach ($act_array as $key => $act) {
    				//$tasks=array_merge($tasks, $this->getAct_Tasks($act));
    				$tasks[$act->getId()]=$this->getAct_Tasks($act);
    			}
    		}
			//print_r($tasks);

    		/*
    		foreach ($tasks as $key => $task) {
    			print_r($task->getShortName());
    		}
			*/
		return $this->render('PedsAccountBundle:Default:account2.html.twig',array('user_rps' => $user_rps,'user_acts' => $acts,'user_tasks' =>$tasks));
        //return $this->render('PedsAccountBundle:Default:account.html.twig');
    }

    public function getRp_Acts($rp)
    {
    	$acts=array();
    	$em = $this->getDoctrine()->getEntityManager();
    	$acts=$em->getRepository('PedsEntitiesBundle:Activity')->findByRp($rp->getId());
    	//For debugging purposes
    	/*
    	$acts_aux=$em->getRepository('PedsEntitiesBundle:Activity')->findByRp($rp->getId());
    	foreach ($acts_aux as $key => $act) {
    		array_push($acts, array("shortName" => $act->getShortName(),"id" => $act->getId()));
    	}
    	*/
    	
        return $acts;
    }
        public function getAct_Tasks($act)
    {
    	$tasks=array();
    	$em = $this->getDoctrine()->getEntityManager();
    	$tasks=$em->getRepository('PedsEntitiesBundle:Task')->findByActivity($act->getId());
    	
    	//For debugging purposes
    	/*
    	$tasks_aux=$em->getRepository('PedsEntitiesBundle:Task')->findByActivity($act->getId());
    	foreach ($tasks_aux as $key => $task) {
    		array_push($tasks, array("shortName" => $task->getShortName(),"id" => $task->getId()));
    	}
    	*/
        return $tasks;
    }
}
