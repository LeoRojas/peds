<?php

namespace Peds\CompareBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CompareController extends Controller
{
    public function indexAction()
    {
    	$em = $this->getDoctrine()->getEntityManager();
    	$rps=$em->getRepository('PedsEntitiesBundle:ReferenceProcess')->findAll();
        return $this->render('PedsCompareBundle:Default:compare.html.twig',array('rps' =>$rps));
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
            if(isset($_POST["right_rp"])){
                    
                    $right_rp_id=substr($_POST["right_rp"],6);
                    $right_rp=$em->getRepository('PedsEntitiesBundle:ReferenceProcess')->find($right_rp_id);
            }
            //$compare_res= $this->compareFunctionBasic($left_rp,$right_rp);
            //$compare_res= $this->compareFunctionTasks($left_rp,$right_rp);
            $task_res=$this->compareFunctionTasks($left_rp,$right_rp);
            return $this->render('PedsCompareBundle:Default:compare_res_tasks.html.twig',array('left_rp' =>$left_rp,'right_rp' =>$right_rp,'task_res' =>$task_res));
            /*
            foreach ($_POST as $key => $value){
                        print_r($value);
                        //array_push($rp_res, $value);
                    }
            */
        }
            else{
                return $this->render('PedsCompareBundle:Default:compare_res.html.twig');
            }
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
        $aux=0;
        foreach ($task_bool as $key => $bool){
            $aux+=$bool;
        }
        //print_r($task_left_rp);
        //RES PRINT
		//print_r($task_bool);
        
		$task_res[0]=$task_left_rp;
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
}
