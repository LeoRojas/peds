<?php

namespace Peds\AccountBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
class TestExportExcelController extends Controller
{
    public function indexAction()
    {
        return $this->render('PedsAccountBundle:Default:test_excel_export.html.twig', array('name' => 'bla'));
    }
	
	public function exportAction()
    {
		$response = new Response(
		strip_tags($_POST['tableData'],'<table><th><tr><td>'),
		200,
		array(
		'Pragma' => 'public',
		'Expires' => '0',
		'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
		'Content-Type' => 'application/force-download',
		'Content-Type' => 'application/octet-stream',
		'Content-Type' => 'application/download',
		'Content-Type' => 'application/vnd.ms-excel',
		'Content-Disposition' => 'attachment;filename=export.xls',
		'Content-Transfer-Encoding' => 'binary',
		));
		return $response;
		//return $this->render('PedsAccountBundle:Default:test_excel_export.html.twig', array('name' => 'bla'));
		//header('Content-Type: application/vnd.ms-excel');
		/*
		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Content-Type: application/force-download");
		header("Content-Type: application/octet-stream");
		header("Content-Type: application/download");
		header("Content-Disposition: attachment;filename=export.xls");
		header("Content-Transfer-Encoding: binary ");
		*/
		
		//echo strip_tags($_POST['tableData'],'<table><th><tr><td>');
		//return strip_tags($_POST['tableData'],'<table><th><tr><td>');
        //return $this->render('PedsAccountBundle:Default:test_excel_export.html.twig', array('name' => 'bla'));
    }
}
