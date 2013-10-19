<?php

namespace Peds\CompareBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ManualCompareController extends Controller
{
    public function indexAction()
    {
    	$em = $this->getDoctrine()->getEntityManager();
    	$rps=$em->getRepository('PedsEntitiesBundle:ReferenceProcess')->findAll();
        return $this->render('PedsCompareBundle:Default:manual_compare.html.twig',array('rps' =>$rps));
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
            if(isset($_POST["left_rp"])){
                    
                    $left_rp_id=substr($_POST["left_rp"],5);
                    $left_rp=$em->getRepository('PedsEntitiesBundle:ReferenceProcess')->find($left_rp_id);
            }
			
            if( (isset($_POST["right_rps"])) && (!empty($_POST["right_rps"])) ){
                    
				//$coverage_aux=$this->mappingCoverageFunction($left_rp,$right_rp);
				//print_r("BANANA");
				//echo '<br>'; 
				//print_r($_POST["right_rps"]);
				$sec_rps=explode(",", $_POST["right_rps"]);
				//echo '<br>'; 
				//print_r($sec_rps);
				$coverage_aux=$this->mappingCoverageFunction($left_rp,$sec_rps[0]);
				$right_rp=$em->getRepository('PedsEntitiesBundle:ReferenceProcess')->find($sec_rps[0]);
				$coverage_res=array();
				$right_rp_array=array();
				foreach($sec_rps as $key => $rp_id){
					$aux_rp=$em->getRepository('PedsEntitiesBundle:ReferenceProcess')->find($rp_id);
					$coverage_res[]=$this->mappingCoverageFunction($left_rp,$aux_rp);
					$right_rp_array[]=$aux_rp;
				}
			}
            return $this->render('PedsCompareBundle:Default:manual_compare_res.html.twig',array('left_rp' =>$left_rp,'right_rp' =>$right_rp,'coverage_res' =>$coverage_res,'right_rp_array' =>$right_rp_array));

        }
            else{
                return $this->render('PedsCompareBundle:Default:manual_compare_res.html.twig');
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
	public function mappingCoverageFunction($left_rp,$right_rp){
	
		$em = $this->getDoctrine()->getEntityManager();
		$base_rp=$em->getRepository('PedsEntitiesBundle:ReferenceProcess')->find($left_rp);
		$sec_rp=$em->getRepository('PedsEntitiesBundle:ReferenceProcess')->find($right_rp);
		$base_rp_acts=array();
		$base_tasks=array();
		$count_used=0;
		$count_nused=0;
		$count_tasks=0;
		$count_sec_tasks=0;
		$user = $this->get('security.context')->getToken()->getUser();
		$res=array();
		$map_res=array();
		$task_res=array();
		$base_rp_acts=$this->getRp_Acts($base_rp);
			foreach($base_rp_acts as $key => $act){
				$aux_tasks=$this->getAct_Tasks($act);
				foreach($aux_tasks as $key => $task){
					$base_tasks[]=$task;
				}
			}
			//Sec_rp task_count
			$sec_rp_acts=$this->getRp_Acts($sec_rp);
			$sec_tasks=array();
			foreach($sec_rp_acts as $key => $act){
				$aux_tasks=$this->getAct_Tasks($act);
				foreach($aux_tasks as $key => $task){
					$sec_tasks[]=$task;
				}
			}
			$count_sec_tasks=count($sec_tasks);
			$count_tasks=count($base_tasks);
			foreach($base_tasks as $task){
				$aux_array=array();
				$aux_array['sname']=$task->getShortName();
				$aux_array['desc']=$task->getDescription();
				$task_res[$task->getId()]=$aux_array;
				//print_r($task->getShortName());
				//$tcomp=$em->getRepository('PedsEntitiesBundle:TaskComp')->findOneByBaseTask($task);
				//findOneBy(array('name' => 'foo', 'price' => 19.99));
				$tcomp=$em->getRepository('PedsEntitiesBundle:TaskComp')->findOneBy(array('baseTask' =>$task,'user' =>$user,'rp' =>$sec_rp));
				if($tcomp){
					$tmap=$em->getRepository('PedsEntitiesBundle:TaskMapping')->findByTcomp($tcomp);
					$map_res[$task->getId()]=count($tmap);
					$count_used++;
				}
				else{
					$map_res[$task->getId()]=0;
					$count_nused++;
				}
			}
			//print_r($map_res);
			//echo '<br>'; 
			//print_r($count_used);
			//echo '<br>';
			//print_r($count_nused);
			$count_array=array();
			$count_array['c_used']=$count_used;
			$count_array['c_nused']=$count_nused;
			$count_array['c_tasks']=$count_tasks;
			$count_array['c_sec_tasks']=$count_sec_tasks;
			$res[0]=$task_res;
			$res[1]=$map_res;
			$res[2]=$count_array;
			return $res;
			
			//$comp_map=$em->getRepository('PedsEntitiesBundle:TaskMapping')->findByTcomp($comp);
    }
	
    //COMPARE FUNCTIONS
    //The return value must me a number [0,1]
    //The parameters are two reference process objects
    public function compareFunctionBasic($left_rp,$right_rp){
        if($left_rp->getId()==$right_rp->getId()){ return 1; }
            else{ return 0; }
    }

    public function compareFunctionTasks($left_rp,$right_rp){
        $task_res=array();
        $task_left_rp=array();
        $task_right_rp=array();
        $task_bool=array();
        //array_push($task_res, 1);
        //array_push($task_res, 0);
        //array_push($task_res, 0);
        //array_push($task_res, 1);
        $em = $this->getDoctrine()->getEntityManager();
        $rps_acts=$em->getRepository('PedsEntitiesBundle:Activity')->findByRp($left_rp->getId());
        foreach ($rps_acts as $key => $act){
            
            $act_tasks=$em->getRepository('PedsEntitiesBundle:Task')->findByActivity($act->getId());

            foreach ($act_tasks as $key => $task) {
                array_push($task_left_rp, $task);
                //For test purposes
                //array_push($task_bool, 1);
            }
           
        }
        $right_rp_acts=$em->getRepository('PedsEntitiesBundle:Activity')->findByRp($right_rp->getId());
        foreach ($right_rp_acts as $key => $act){
            
            $right_act_tasks=$em->getRepository('PedsEntitiesBundle:Task')->findByActivity($act->getId());

            foreach ($right_act_tasks as $key => $task) {
                array_push($task_right_rp, $task);
            }
           
        }
        $task_bool=$this->taskArrayFunction($task_left_rp,$task_right_rp,0);
		//$task_comps=$this->taskComparisonsArrayFunction($task_left_rp,$task_right_rp);
        $aux=0;
        foreach ($task_bool as $key => $bool){
            $aux+=$bool;
        }
        //print_r($task_left_rp);
        //print_r($task_bool);
		
        $task_res[0]=$task_left_rp;
        //$task_res[1]=$task_bool;
		$task_res[1]=$task_bool;
        $task_res[2]=count($task_right_rp);
        $task_res[3]=$aux;
        return $task_res;
        //return $this->render('PedsCompareBundle:Default:compare_res_tasks.html.twig',array('left_rp' =>$left_rp,'right_rp' =>$right_rp,'task_res' =>$task_res));
    }

    //mode=0 => shortname compare
    //mode=1 => description compare
    //mode=2 => shortname && description compare
     public function taskArrayFunction($left_rp_tasks,$right_rp_tasks,$mode){
     $task_bool=array();
     $count=0;

     foreach ($left_rp_tasks as $key => $left_task){
        $task_bool[$count]=0;
        $count++;
     }
     $count=0;
     foreach ($left_rp_tasks as $key => $left_task){

            foreach ($right_rp_tasks as $key => $right_task) {
                if($task_bool[$count]==0){
                    if($mode==0){
                        if(($left_task->getShortName()) == ($right_task->getShortName())){$task_bool[$count]=1;}

                    }
                    elseif($mode==1){
                        if($left_task->getDescription() == $right_task->getDescription()){$task_bool[$count]=1;}
                    }
                    elseif($mode==2){
                        if( ($left_task->getShortName() == $right_task->getShortName()) && ($left_task->getDescription() == $right_task->getDescription()) ){$task_bool[$count]=1;}
                    }
                    else{
                    //error
                        return $task_bool;
                    }
                }
                else{break;}

            }
            $count++;
           
        }
        return $task_bool;
    }
	public function taskComparisonsArrayFunction($left_rp_tasks,$right_rp_tasks){
     $task_bool=array();
     $count=0;
	$em = $this->getDoctrine()->getEntityManager();
     foreach ($left_rp_tasks as $key => $left_task){
        $task_bool[$count]=0;
        $count++;
     }
     $count=0;
	 
     foreach ($left_rp_tasks as $key => $left_task){

            foreach ($right_rp_tasks as $key => $right_task) {
                if($task_bool[$count]==0){
				$left_rp_id=$_POST["left_rp"];
				$right_rp_id=$_POST["right_rp"];
				
			$comparisons=array();
			$comparisons_aux=$em->getRepository('PedsEntitiesBundle:TaskComp')->findAll();
		
			foreach($comparisons_aux as $key => $comp){
				if(($comp->getTask1()->getActivity()->getRp()->getId()==$left_rp_id && $comp->getTask2()->getActivity()->getRp()->getId()==$right_rp_id)
				|| ($comp->getTask1()->getActivity()->getRp()->getId()==$right_rp_id && $comp->getTask2()->getActivity()->getRp()->getId()==$left_rp_id)){
				$comparisons[]=$comp;
				}
			}
			$comp_array=$this->comparisonsToArray($comparisons);
                }
                else{break;}

            }
            $count++;   
        }
        return $task_bool;
    }
}
