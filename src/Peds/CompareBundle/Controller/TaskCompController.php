<?php

namespace Peds\CompareBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Peds\CompareBundle\Form\TaskCompType;
use Peds\EntitiesBundle\Entity\TaskComp;

class TaskCompController extends Controller
{
    public function indexAction()
    {
    	$em = $this->getDoctrine()->getEntityManager();
    	//$comparisons=$em->getRepository('PedsEntitiesBundle:TaskComp')->findAll();
		$comparisons=array();
		$comp_mappings=array();
		//$user_id=$_POST["user_id"];
		$user = $this->get('security.context')->getToken()->getUser();
		$comparisons_aux=$em->getRepository('PedsEntitiesBundle:TaskComp')->findByUser($user);
		foreach($comparisons_aux as $key => $comp){
			
				//if($comp->getBaseTask()->getActivity()->getRp()->getId()==$left_rp_id){
					$comparisons[]=$comp;
					$comp_map=$em->getRepository('PedsEntitiesBundle:TaskMapping')->findByTcomp($comp);
					$comp_mappings[]=$comp_map;
				
			}
        return $this->render('PedsCompareBundle:Default:task_comp.html.twig',array('comparisons' =>$comparisons,'mappings' =>$comp_mappings));
    }
	
	public function editAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $defaultData = array('');
        $comp = $em->getRepository('PedsEntitiesBundle:TaskComp')->find($id);
        if (!$comp) {
            throw $this->createNotFoundException(
                'No comparison found for id '.$id
            );
        }
        //$comp = new TaskComp();
        $form = $this->createForm(new TaskCompType(), $comp);

        if ($this->getRequest()->isMethod('POST')) {
        $form->bind($this->getRequest());

            if ($form->isValid()) {
            print_r($_POST);
            $comp = $form->getData();
            $em->persist($comp);
            $em->flush();
            print_r("Comparison entry updated");
            //$this->get('session')->setFlash('notice', 'Depósito Realizado!');
            

            }//isvalid
        }
    	$em = $this->getDoctrine()->getEntityManager();
    	$comparisons=$em->getRepository('PedsEntitiesBundle:TaskComp')->findAll();
        return $this->render('PedsCompareBundle:Default:task_comp_edit.html.twig',array('form' => $form->createView(),'comparisons' =>$comparisons, 'id' => $id ));
    }
	
	public function removeIdAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $comp = $em->getRepository('PedsEntitiesBundle:TaskComp')->find($id);

        if (!$comp) {
             throw $this->createNotFoundException(
                'No Comparison found for id '.$id);
        }
        $em->remove($comp);
        $em->flush();
        $this->get('session')->getFlashBag()->add('notice', 'TaskComparison with id='.$id.' was deleted successfully!');
		$comparisons=$em->getRepository('PedsEntitiesBundle:TaskComp')->findAll();
        return $this->render('PedsCompareBundle:Default:task_comp.html.twig',array('comparisons' =>$comparisons));
        //return $this->render('PedsRefProcBundle:Default:task_remove_id.html.twig',array('id' => $id));
    }
	
	public function searchAction()
    {
		$em = $this->getDoctrine()->getEntityManager();
		//return $this->render('PedsCompareBundle:Default:task_comp.html.twig',array('comparisons' =>$comparisons));
		$rps=$em->getRepository('PedsEntitiesBundle:ReferenceProcess')->findAll();
		$users=$em->getRepository('PedsEntitiesBundle:User')->findAll();
		return $this->render('PedsCompareBundle:Default:task_comp_search.html.twig',array('rps' =>$rps, 'users' =>$users));
	}
	
	public function searchComphByRpAction()
    {
		$left_rp_id=$_POST["left_rp"];
		$right_rp_id=$_POST["right_rp"];
		$em = $this->getDoctrine()->getEntityManager();
			$comparisons=array();
			//$comp_mappings=array();
			$comparisons_aux=$em->getRepository('PedsEntitiesBundle:TaskComp')->findByRp($right_rp_id);
		
			foreach($comparisons_aux as $key => $comp){
			
				if($comp->getBaseTask()->getActivity()->getRp()->getId()==$left_rp_id){
					$comparisons[]=$comp;
					$comp_map=$em->getRepository('PedsEntitiesBundle:TaskMapping')->findByTcomp($comp);
					//$comp_mappings[]=$comp_map;
				}
			}
			$comp_array=$this->comparisonsToArray($comparisons);
		$response = array("code" => 200, "success" => true, "msg" => "Success: searcComphByRp successfully!","left_rp_id" =>$left_rp_id,"right_rp_id" =>$right_rp_id,"comparisons" =>$comp_array);
    
	return new Response(json_encode($response));
	}
	public function copyCompAction(){
	
		$response = array("code" => 200, "success" => true, "msg" => "Success: CopyComp successfully!");
    
	return new Response(json_encode($response));
	}
	public function copyAllCompAction(){
	
		$response = array("code" => 200, "success" => true, "msg" => "Success: CopyAllComp successfully!");
    
	return new Response(json_encode($response));
	}
	public function comparisonsToArray($comparisons)
    {
		$em = $this->getDoctrine()->getEntityManager();
		$comp_array = array();
		foreach($comparisons as $key => $comp){
			$aux_array = array();
			//getShortName getObs
			$aux_array['base_task_sname']=$comp->getBaseTask()->getShortName();
			$comp_map=$em->getRepository('PedsEntitiesBundle:TaskMapping')->findByTcomp($comp->getId());
			$aux_map_array =array();
			foreach($comp_map as $key => $map){
				$aux_map_array[]=$map->getTask()->getShortName();
			}
			$aux_array['mappings']=$aux_map_array;
			$aux_array['mat_sname']=$comp->getMatching()->getShortName();
			$aux_array['obs']=$comp->getObs();
			$aux_array['comp_id']=$comp->getId();
			$aux_array['user']=$comp->getUser()->getUsername();
			$aux_array['user_id']=$comp->getUser()->getId();
			$aux_array['base_rp_sname']=$comp->getBaseTask()->getActivity()->getRp()->getShortName();
			$aux_array['sec_rp_sname']=$comp->getRp()->getShortName();
			$comp_array[]=$aux_array;
		}
		return $comp_array;
	}
	public function searchComphByUserAction()
    {
		$em = $this->getDoctrine()->getEntityManager();
		$comparisons=array();
		//$comp_mappings=array();
		$user_id=$_POST["user_id"];
		$comparisons_aux=$em->getRepository('PedsEntitiesBundle:TaskComp')->findByUser($user_id);
		foreach($comparisons_aux as $key => $comp){
			
				//if($comp->getBaseTask()->getActivity()->getRp()->getId()==$left_rp_id){
					$comparisons[]=$comp;
					$comp_map=$em->getRepository('PedsEntitiesBundle:TaskMapping')->findByTcomp($comp);
					//$comp_mappings[]=$comp_map;
				
			}
		$comp_array=$this->comparisonsToArray($comparisons);
		$response = array("code" => 200, "success" => true, "msg" => "Success: searcComphByUser successfully!","comparisons" =>$comp_array);
    
	return new Response(json_encode($response));
	}

}