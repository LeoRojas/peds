<?php

namespace Peds\CompareBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ChartController extends Controller
{
    public function indexAction()
    {
		$em = $this->getDoctrine()->getEntityManager();
		//$brp=$em->getRepository('PedsEntitiesBundle:ReferenceProcess')->find(45);
		//$srp=$em->getRepository('PedsEntitiesBundle:ReferenceProcess')->find(49);
		//$score=$this->getCoverage($brp,$srp);
		$score=0;
    	$rps=$em->getRepository('PedsEntitiesBundle:ReferenceProcess')->findAll();
		return $this->render('PedsCompareBundle:Default:chart_rp.html.twig',array('score' => $score,'rps' =>$rps));
        //return $this->render('PedsCompareBundle:Default:chart.html.twig',array('score' => $score));
    }
	    public function chartDataAction()
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
				$coverage_res=array();
				$right_rp_array=array();
				$base_rp_task_count=$this->getTaskCount($left_rp);
				foreach($sec_rps as $key => $rp_id){
					$aux_rp=$em->getRepository('PedsEntitiesBundle:ReferenceProcess')->find($rp_id);
					$coverage_res[]=$this->getCoverage($left_rp,$aux_rp,$base_rp_task_count);
					$right_rp_array[]=$aux_rp;
				}
			}
            return $this->render('PedsCompareBundle:Default:chart.html.twig',array('left_rp' =>$left_rp,'right_rp' =>0,'coverage_res' =>$coverage_res,'right_rp_array' =>$right_rp_array));

        }
            else{
                return $this->render('PedsCompareBundle:Default:manual_compare_res.html.twig');
            }
    }
	public function getTaskCount($rp){
		$base_rp_acts=array();
		$base_tasks=array();
		$base_rp_acts=$this->getRp_Acts($rp);
			foreach($base_rp_acts as $key => $act){
				$aux_tasks=$this->getAct_Tasks($act);
				foreach($aux_tasks as $key => $task){
					$base_tasks[]=$task;
				}
			}
		$count_tasks=count($base_tasks);
		return $count_tasks;
	}
		 public function getRp_Acts($rp)
    {
    	$acts=array();
    	$em = $this->getDoctrine()->getEntityManager();
    	$acts=$em->getRepository('PedsEntitiesBundle:Activity')->findByRp($rp->getId());
    	
        return $acts;
    }
        public function getAct_Tasks($act)
    {
    	$tasks=array();
    	$em = $this->getDoctrine()->getEntityManager();
    	$tasks=$em->getRepository('PedsEntitiesBundle:Task')->findByActivity($act->getId());
    	
        return $tasks;
    }
	public function getCoverage($base_rp,$sec_rp,$task_count)
    {
		$em = $this->getDoctrine()->getEntityManager();
		$user = $this->get('security.context')->getToken()->getUser();
		$comparisons=array();
		$count=0;
		$comparisons_aux=$em->getRepository('PedsEntitiesBundle:TaskComp')->findBy(array('user' => $user,'rp'=>$sec_rp));
					foreach($comparisons_aux as $key => $comp){

						if($comp->getBaseTask()->getActivity()->getRp()->getId()==$base_rp->getId()){
							$comparisons[]=$comp;
							$count+=$comp->getMatching()->getScore();
						}
					}
		//$comp_total=count($comparisons);
		if($task_count>0){
			return ($count/$task_count)*100;
		}
		else{
			return 0;
		}
        //return $this->render('PedsCompareBundle:Default:chart.html.twig');
    }
}
