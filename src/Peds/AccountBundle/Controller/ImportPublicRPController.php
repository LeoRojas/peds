<?php

namespace Peds\AccountBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Peds\EntitiesBundle\Entity\ReferenceProcess;
use Peds\EntitiesBundle\Entity\Role;
use Peds\EntitiesBundle\Entity\Product;
use Peds\EntitiesBundle\Entity\Activity;
use Peds\EntitiesBundle\Entity\Task;
use Peds\EntitiesBundle\Entity\InputProducts;
use Peds\EntitiesBundle\Entity\OutputProducts;

class ImportPublicRPController extends Controller
{
    public function indexAction()
    {
        /*$form = $this->createForm(new RPType(),null,array(
		'translator_service' => $this->get('translator')
		));
		*/
		$em = $this->getDoctrine()->getEntityManager();
    	$public_rps=$em->getRepository('PedsEntitiesBundle:ReferenceProcess')->findByVisibility("PUBLIC");
        return $this->render('PedsAccountBundle:Default:import_public_rp_list.html.twig', array('form' => 1,'public_rps' => $public_rps));
    }
	
	public function copyAction($id)
    {
	    $em = $this->getDoctrine()->getEntityManager();
    	$rp = $em->getRepository('PedsEntitiesBundle:ReferenceProcess')->find($id);

    	if (!$rp) {
       		 throw $this->createNotFoundException(
            	'No ReferenceProcess found for id '.$id);
    	}
		$user = $this->get('security.context')->getToken()->getUser();
		if($rp->getOwner()==$user){
		
			$this->get('session')->getFlashBag()->add('error', 'RPs that you alredy own cannot be copied!');
		}
		else{
			$rp_name=$rp->getShortName();
			$this->get('session')->getFlashBag()->add('notice', 'RP '.$rp_name.' was copied successfully to your account!');
		}
		//Clone RP attributes
		$rp_clone = new ReferenceProcess();
		$rp_clone = clone $rp;
		$prods_mapping=array();
		$role_mapping=array();
		//Clone ROLES and PRODUCTS
		$prods = $em->getRepository('PedsEntitiesBundle:Product')->findByRp($rp);
		    foreach ($prods as $key => $prod) {
				$product_clone = new Product();
				$this->get('session')->getFlashBag()->add('notice', 'old clone id '.$product_clone->getId());
				$product_clone = clone $prod;
				$product_clone->setRp($rp_clone);
				$em->persist($product_clone);
				$prods_mapping[$prod->getId()]=$product_clone;
				$this->get('session')->getFlashBag()->add('notice', 'clone id '.$product_clone->getId());
				//clone ids its the same as prod->getId, because the new id is generated when flush is called
				//$prods_mapping[$prod->getId()]=$product_clone->getId();
    		}
		$roles = $em->getRepository('PedsEntitiesBundle:Role')->findByRp($rp);
			foreach ($roles as $key => $role) {
				$role_clone = new Role();
				$role_clone = clone $role;
				$role_clone->setRp($rp_clone);
				$em->persist($role_clone);
				$role_mapping[$role->getId()]=$role_clone;
    		}
		//
		//Clone ACTIVITIES
		$acts = $em->getRepository('PedsEntitiesBundle:Activity')->findByRp($rp);
		    foreach ($acts as $key => $act) {
				$activity_clone = new Activity();
				$activity_clone = clone $act;
				$activity_clone->setRp($rp_clone);
				$em->persist($activity_clone);
				
				//Clone TASKS
				$tasks = $em->getRepository('PedsEntitiesBundle:Task')->findByActivity($act);
				foreach ($tasks as $key => $task) {
					$task_clone = new Task();
					$task_clone = clone $task;
					//$role_mapping[$role->getId()]=$role_clone;
					$task_clone->setActivity($activity_clone);
					if($task->getRole()){
						$task_clone->setRole($role_mapping[$task->getRole()->getId()]);
					}
					$em->persist($task_clone);
					
					//Clone INPUT PRODUCTS and OUTPUT PRODUCTS
					$IProds = $em->getRepository('PedsEntitiesBundle:InputProducts')->findByTask($task);
					foreach ($IProds as $key => $iprod) {
						$iprod_clone = new InputProducts();
						$iprod_clone = clone $iprod;
						$iprod_clone->setTask($task_clone);
						$prod_key=$iprod->getProduct()->getId();
						$iprod_clone->setProduct($prods_mapping[$prod_key]);
						$em->persist($iprod_clone);
					}
					
					$OProds = $em->getRepository('PedsEntitiesBundle:OutputProducts')->findByTask($task);
					foreach ($OProds as $key => $oprod) {
						$oprod_clone = new OutputProducts();
						$oprod_clone = clone $oprod;
						$oprod_clone->setTask($task_clone);
						$prod_key=$oprod->getProduct()->getId();
						$oprod_clone->setProduct($prods_mapping[$prod_key]);
						$em->persist($oprod_clone);
					}
				}
    		}
		$rp_clone->setOwner($user);
		$em->persist($rp_clone);
		//$B->setId(null);
		//$em->remove($rp);
		$em->flush();
		
        
		$url = $this->getRequest()->headers->get("referer");
		return $this->redirect($url);
        //return $this->render('PedsRefProcBundle:Default:ref_proc_remove_id.html.twig',array('id' => $id));
	}
}