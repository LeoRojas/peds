<?php

namespace Peds\AccountBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Peds\EntitiesBundle\Entity\ReferenceProcess;
use Peds\EntitiesBundle\Entity\Activity;
use Peds\EntitiesBundle\Entity\Task;

class LoadRPController extends Controller
{
    public function indexAction()
    {
        return $this->render('PedsAccountBundle:Default:load_rp.html.twig');
    }
	public function createTasks($xml_tasks,$xml_file_name)
    {
		$em = $this->getDoctrine()->getEntityManager();
		$user = $this->get('security.context')->getToken()->getUser();
		$url = $this->getRequest()->headers->get("referer");
		
		//RP
		$rp = new ReferenceProcess();
		$rp_name=substr($xml_file_name,0,strlen($xml_file_name)-4);
		//$rp->setShortName("RP_XML");
		$rp->setShortName($rp_name);
		$rp->setDescription("Reference Process created from .xml file");
		$rp->setVisibility("Private");
		$rp->setWorkflow("No");
		$rp->setOwner($user);
    	$em->persist($rp);
        $this->get('session')->getFlashBag()->add('notice', 'RP '.$rp_name.' created successfully!');
		
		//Activity
		$activity = new Activity();
		$activity->setRp($rp);
		//$activity->setShortName("ACTIVITY_XML");
		$activity->setShortName($rp_name."ACT");
		$activity->setDescription("Autogenerated Activity to store Tasks from .xml file");
		$em->persist($activity);
		$this->get('session')->getFlashBag()->add('notice', 'Activity '.$rp_name.'ACT'.' created successfully!');
		
		//Tasks
		foreach($xml_tasks as $xml_t){
			$task = new Task();
			$task->setShortName($xml_t['presentationName']);
			$t_desc="";
			if($xml_t['Description']==""){
				if($xml_t->MainDescription!=""){
					$t_desc=$xml_t->MainDescription;
				}
			}
			else{
				$t_desc=$xml_t['Description'];
			}
			$task->setDescription($t_desc);
			//$task->setDescription("Autogenerated Task from .xml file");
			$task->setDetail("a bit");
			$task->setActivity($activity);
			$em->persist($task);
			//Add task name to the flash
			$this->get('session')->getFlashBag()->add('notice', 'Task '.$xml_t['presentationName'].' created successfully!');
		}
		$em->flush();
        return $this->redirect($url);
    }

	
	public function loadAction()
    {
	if(!isset($_FILES["file"])){return $this->render('PedsAccountBundle:Default:load_rp.html.twig');}
	//$allowedExts = array("gif", "jpeg", "jpg", "png");
	$allowedExts = array("xml");
	$temp = explode(".", $_FILES["file"]["name"]);
	$extension = end($temp);
		if (($_FILES["file"]["type"] == "text/xml")
		&& ($_FILES["file"]["size"] < 2000000)
		&& in_array($extension, $allowedExts))
		  {
		  if ($_FILES["file"]["error"] > 0)
			{
			echo "Error: " . $_FILES["file"]["error"] . "<br>";
			}
		  else
			{
			
			//echo "Upload: " . $_FILES["file"]["name"] . "<br>";
			//echo "Type: " . $_FILES["file"]["type"] . "<br>";
			//echo "Size: " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
			//echo "Stored in: " . $_FILES["file"]["tmp_name"] . "<br>";
			
			//$mysongs = simplexml_load_file($_FILES["file"]["tmp_name"]);
			//echo $mysongs->song[0]->artist . "<br>";
			//echo $mysongs->song[2]->title . "<br>";
			//echo $mysongs->song[1]['dateplayed'];
			$xml_file = simplexml_load_file($_FILES["file"]["tmp_name"]);
			//print_r($xml_file);
			//$res = $xml_file->xpath('//MethodPackage[@presentationName]');
			//$res = $xml_file->xpath('//@presentationName');
			$res = $xml_file->xpath("//BreakdownElement[@presentationName  and (@xsi:type='uma:TaskDescriptor' or @xsi:type='uma:Task')]");
			foreach($res as $pack){
				//echo "Name: " . $pack->getName() . "  ";
				//echo "presentationName: " . $pack['presentationName'] ."<br>";
				
				//if(!empty($pack
				//print_r($pack);
				/*
				foreach($pack->attributes() as $a => $b){
					echo $a, '="',$b,"\"\n";
				}
				*/
			}
			if (count($res) < 1) {
				$this->get('session')->getFlashBag()->add('error', 'There are no tasks in the xml file');
				$load_res="Invalid file";
			}
				else{
					$load_res="Valid file";
					$this->get('session')->getFlashBag()->add('notice', 'Valid xml file!');
					$this->createTasks($res,$_FILES["file"]["name"]);
				}
			}
		  }
		else
		  {
		  $this->get('session')->getFlashBag()->add('error', 'Invalid file, must have xml extension!');
		  $load_res="Invalid file";
		  }
		return $this->render('PedsAccountBundle:Default:load_rp.html.twig',array('load_res' =>$load_res));
	}
}
