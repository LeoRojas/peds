<?php

namespace Peds\CompareBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Peds\EntitiesBundle\Entity\TaskComp;
use Peds\EntitiesBundle\Entity\TaskMapping;

class ManualTaskCompareController extends Controller
{
    public function indexAction()
    {
    	$em = $this->getDoctrine()->getEntityManager();
		$rps=array();
    	$rps_aux=$em->getRepository('PedsEntitiesBundle:ReferenceProcess')->findAll();
				foreach($rps_aux as $key => $rp){
				if($this->haveTask($rp)){
				//echo $rp->getShortName();
				//echo "\n";
				$rps[]=$rp;
				}
				
			}
        return $this->render('PedsCompareBundle:Default:manual_task_compare_rp.html.twig',array('rps' =>$rps));
    }

    public function compareAction()
    {
    	$em = $this->getDoctrine()->getEntityManager();
    	//$rps=$em->getRepository('PedsEntitiesBundle:ReferenceProcess')->findAll();
        $request = $this->getRequest();
        if ($request->getMethod() == 'POST') 
        {
            $rp_res=array();
            //print_r($_POST);
            if(isset($_POST["left_rp"]) && !empty($_POST["left_rp"])){
                    $left_rp_id=substr($_POST["left_rp"],5);
                    $left_rp=$em->getRepository('PedsEntitiesBundle:ReferenceProcess')->find($left_rp_id);
					$left_rp_acts=array();
					$left_rp_tasks=array();
					$left_rp_acts[$left_rp->getId()]=$this->getRp_Acts($left_rp);
						foreach ($left_rp_acts[$left_rp->getId()] as $key => $act) {
							//$tasks=array_merge($tasks, $this->getAct_Tasks($act));
							$left_rp_tasks[$act->getId()]=$this->getAct_Tasks($act);
						}
					/*
					foreach ($left_rp_tasks as $key => $task_array) {
						foreach($task_array as $key => $task){
							print_r($task->getShortName());
						}
					}
					*/
            }
            if(isset($_POST["right_rp"]) && !empty($_POST["right_rp"])){
                    $right_rp_id=substr($_POST["right_rp"],6);
                    $right_rp=$em->getRepository('PedsEntitiesBundle:ReferenceProcess')->find($right_rp_id);
					$right_rp_acts=array();
					$right_rp_tasks=array();
					$right_rp_acts[$right_rp->getId()]=$this->getRp_Acts($right_rp);
						foreach ($right_rp_acts[$right_rp->getId()] as $key => $act) {
							//$tasks=array_merge($tasks, $this->getAct_Tasks($act));
							$right_rp_tasks[$act->getId()]=$this->getAct_Tasks($act);
						}
					/*
					foreach ($right_rp_tasks as $key => $task_array) {
						foreach($task_array as $key => $task){
							print_r($task->getShortName());
						}
					}
					*/
            }

			if(isset($left_rp_id) && isset($right_rp_id)){
				$user = $this->get('security.context')->getToken()->getUser();
				$comparisons=array();
				$comp_mappings=array();
				//$comparisons_aux=$em->getRepository('PedsEntitiesBundle:TaskComp')->findByUser($user);
				$comparisons_aux=$em->getRepository('PedsEntitiesBundle:TaskComp')->findBy(array('user' => $user,'rp'=>$right_rp_id));
					foreach($comparisons_aux as $key => $comp){
						/*
						if(($comp->getTask1()->getActivity()->getRp()->getId()==$left_rp_id && $comp->getTask2()->getActivity()->getRp()->getId()==$right_rp_id)
						|| ($comp->getTask1()->getActivity()->getRp()->getId()==$right_rp_id && $comp->getTask2()->getActivity()->getRp()->getId()==$left_rp_id)){
							$comparisons[]=$comp;
						}
						*/
						//$comparison=$em->getRepository('PedsEntitiesBundle:TaskComp')->findOneBy(array('baseTask' => $t1_id,'rp' =>$rp_id,'user' => $user));
						if($comp->getBaseTask()->getActivity()->getRp()->getId()==$left_rp_id){
							$comparisons[]=$comp;
							$comp_map=$em->getRepository('PedsEntitiesBundle:TaskMapping')->findByTcomp($comp);
							$comp_mappings[]=$comp_map;
						}
					}
				return $this->render('PedsCompareBundle:Default:manual_task_compare.html.twig',array('left_rp' =>$left_rp,'right_rp' =>$right_rp, 'comparisons'=>$comparisons,'left_rp_tasks' =>$left_rp_tasks, 'right_rp_tasks' => $right_rp_tasks,'mappings' =>$comp_mappings));
            }
			else{
				////Add flash or some kind of message telling to the user that selecting two rps is needed
				$em = $this->getDoctrine()->getEntityManager();
				$rps=$em->getRepository('PedsEntitiesBundle:ReferenceProcess')->findAll();
				return $this->render('PedsCompareBundle:Default:manual_task_compare_rp.html.twig',array('rps' =>$rps));
			}
			/*
            foreach ($_POST as $key => $value){
                        print_r($value);
                        //array_push($rp_res, $value);
                    }
            */
        }
            else{
				//ERROR         
            }
    }
	public function haveTask($rp){
		$em = $this->getDoctrine()->getEntityManager();
		$acts=$em->getRepository('PedsEntitiesBundle:Activity')->findByRp($rp->getId());
		if(!$acts){return false;}
		else{
			foreach($acts as $key => $act){
				$tasks=$em->getRepository('PedsEntitiesBundle:Task')->findByActivity($act->getId());
				if($tasks){return true;}
			}
			return false;
		}
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
	public function checkAction(){
		$request = $this->container->get('request');  
		$t1_id=$_POST["left_task_id"];
		//$t2_id=$_POST["right_task_id"];
		$rp_id=$_POST["right_rp_id"];
		$em = $this->getDoctrine()->getEntityManager();
		$user = $this->get('security.context')->getToken()->getUser();
		
		$comparison=$em->getRepository('PedsEntitiesBundle:TaskComp')->findOneBy(array('baseTask' => $t1_id,'rp' =>$rp_id,'user' => $user));
		//$comparison_left=$em->getRepository('PedsEntitiesBundle:TaskComp')->findOneBy(array('task1' => $t1_id, 'task2' => $t2_id,'user' => $user));
		//$comparison_right=$em->getRepository('PedsEntitiesBundle:TaskComp')->findOneBy(array('task1' => $t2_id, 'task2' => $t1_id,'user' => $user));
		
		//if ($comparison_left || $comparison_right) {
		if ($comparison) {
			/*
			throw $this->createNotFoundException(
				'Comparison found for task with id='.$t1_id.' and task with id='.$t2_id
			);
			*/
			//$this->get('session')->getFlashBag()->add('notice', 'The comparison alredy exists!');
			$response = array("code" => 200, "success" => false, "msg" => "Error: The comparison alredy exists!");
		}
		else{
			//$this->get('session')->getFlashBag()->add('notice', 'Comparison created successfully!');
			$response = array("code" => 200, "success" => true, "msg" => "Non existent comparison");
		}
		//you can return result as JSON
		return new Response(json_encode($response));
		
	}
	public function createMappingRows($right_tasks_array,$tcomp_id)
    {
		$em = $this->getDoctrine()->getEntityManager();
		$tcomp = $em->getRepository('PedsEntitiesBundle:TaskComp')->find($tcomp_id);
		foreach($right_tasks_array as $key => $task_id){
				
				$tmap = new TaskMapping();
				//$tmap->setTcompId($tcomp);
				$tmap->setTcomp($tcomp);
				$rtask=$em->getRepository('PedsEntitiesBundle:Task')->find($task_id);
				//$tmap->setTaskId($rtask);
				$tmap->setTask($rtask);
				$em->persist($tmap);
		}
		$em->flush();
	}
	//Create an empty comparison (no observations and matching code N, not achieved
	public function createCompAction()
    {
	$em = $this->getDoctrine()->getEntityManager();
	$user = $this->get('security.context')->getToken()->getUser();
	$right_tasks_array_id = $_POST["right_tasks_id"];
	$right_rp_id = $_POST["right_rp_id"];
		$tcomp = new TaskComp();
				
		$tbase=$em->getRepository('PedsEntitiesBundle:Task')->find($_POST["left_task_id"]);
		$tcomp->setBaseTask($tbase);
		//$t2=$em->getRepository('PedsEntitiesBundle:Task')->find($_POST["right_task_id"]);
		//$tcomp->setTask2($t2);
		$right_rp=$em->getRepository('PedsEntitiesBundle:ReferenceProcess')->find($right_rp_id);
		$tcomp->setRp($right_rp);
		$mat=$em->getRepository('PedsEntitiesBundle:Matching')->findOneByCode("N");
		$tcomp->setMatching($mat);
				
		$tcomp->setObs("");
		$tcomp->setUser($user);
		$em->persist($tcomp);
		
		$em->flush();
		$this->createMappingRows($right_tasks_array_id,$tcomp->getId());
		$rtasks_sname=$this->getTasksSname($right_tasks_array_id);
		$response = array("code" => 200, "success" => true, "msg" => "Success: TaskComparison created successfully!","created_id" =>$tcomp->getId(),'rtasks_id' =>$right_tasks_array_id,'rtasks_sname' =>$rtasks_sname);
		//$this->get('session')->getFlashBag()->add('notice', 'TaskComparison created successfully!');
		return new Response(json_encode($response));
	}
	public function getTasksSname($right_tasks_array_id){
		$res=array();
		$em = $this->getDoctrine()->getEntityManager();
		foreach($right_tasks_array_id as $key => $task_id){
				
				$rtask=$em->getRepository('PedsEntitiesBundle:Task')->find($task_id);
				$res[]=$rtask->getShortName();
		}
		return $res;
	}
	public function editCompAction()
    {
		$id=$_POST["comp_id"];
		$em = $this->getDoctrine()->getEntityManager();
        $defaultData = array('');
        $tcomp = $em->getRepository('PedsEntitiesBundle:TaskComp')->find($id);
        if (!$tcomp) {
            throw $this->createNotFoundException(
                'No task comparison found for id '.$id
            );
        }
		$o_score=$tcomp->getMatching()->getScore();
		$mat=$em->getRepository('PedsEntitiesBundle:Matching')->findOneByCode($_POST["matching"]);
		$tcomp->setMatching($mat);
		$tcomp->setObs($_POST["observations"]);
		$em->flush();
		$n_score=$mat->getScore();
		$response = array("code" => 200, "success" => true, "msg" => "Success: TaskComparison updated successfully!","updated_id" =>$tcomp->getId(),"old_score" => $o_score,"new_score" => $n_score);
    
	return new Response(json_encode($response));
	}
	public function editMappingAction(){
		
		$em = $this->getDoctrine()->getEntityManager();
		$comp_id=$_POST["comp_id"];
		$right_tasks_id_array=$_POST["map_array"];
		$comp_map=$em->getRepository('PedsEntitiesBundle:TaskMapping')->findByTcomp($comp_id);
		//Delete all mapping rows
		foreach($comp_map as $key => $map){
			$em->remove($map);
		}
		$em->flush();
		//Create new mapping rows
		$this->createMappingRows($right_tasks_id_array,$comp_id);

		$response = array("code" => 200, "success" => true, "msg" => "Success: Mapping edited successfully!","comp_id" =>$comp_id);
		
	return new Response(json_encode($response));
	}
	public function removeCompAction()
    {
		$id=$_POST["task_id"];
		$em = $this->getDoctrine()->getEntityManager();
        $tcomp = $em->getRepository('PedsEntitiesBundle:TaskComp')->find($id);

        if (!$tcomp) {
             throw $this->createNotFoundException(
                'No task comparison found for id '.$id);
        }
		$o_score=$tcomp->getMatching()->getScore();
        $em->remove($tcomp);
        $em->flush();
        //$this->get('session')->getFlashBag()->add('notice', 'Task with id='.$id.' was deleted successfully!');
		$response = array("code" => 200, "success" => true, "msg" => "Success: TaskComparison deleted successfully!","deleted_id" =>$id,"old_score" => $o_score);
    
	return new Response(json_encode($response));
	}
	public function createAction()
    {
	$em = $this->getDoctrine()->getEntityManager();
	$user = $this->get('security.context')->getToken()->getUser();
	$url = $this->getRequest()->headers->get("referer");
	echo "ENTRO AL createAction";
	print_r("La url referer es");
	print_r($url);
    $rps=$em->getRepository('PedsEntitiesBundle:ReferenceProcess')->findAll();
	
    //$form = $this->createForm(new TaskType(), $task);

		if ($this->getRequest()->isMethod('POST')) {
			//$form->bind($this->getRequest());

			//if ($form->isValid()) {
				//$url = $this->getRequest()->headers->get("referer");
				print_r("Entro al post");
				print_r($_POST);
				$tcomp = new TaskComp();
				
				$t1=$em->getRepository('PedsEntitiesBundle:Task')->find($_POST["left_task_id"]);
				$tcomp->setTask1($t1);
				
				$t2=$em->getRepository('PedsEntitiesBundle:Task')->find($_POST["right_task_id"]);
				$tcomp->setTask2($t2);
				
				$mat=$em->getRepository('PedsEntitiesBundle:Matching')->findOneByCode($_POST["comp_select"]);
				$tcomp->setMatching($mat);
				
				$tcomp->setObs($_POST["obs_text"]);
				$tcomp->setUser($user);
				$em->persist($tcomp);
				$em->flush();
				$this->get('session')->getFlashBag()->add('notice', 'TaskComparison created successfully!');
				
				$this->get('session')->getFlashBag()->add('notice', 'Entro al create y entro al POST');
				return $this->render('PedsCompareBundle:Default:manual_task_compare_rp.html.twig',array('rps' =>$rps));
				//return $this->redirect($url);

			//}//isvalid
		}
        //return $this->render('PedsRefProcBundle:Default:task.html.twig', array('form' => $form->createView(),));
		//return $this->redirect($url);
		
        return $this->render('PedsCompareBundle:Default:manual_task_compare_rp.html.twig',array('rps' =>$rps));
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
