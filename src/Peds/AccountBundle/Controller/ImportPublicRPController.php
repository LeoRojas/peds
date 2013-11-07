<?php

namespace Peds\AccountBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Peds\EntitiesBundle\Entity\ReferenceProcess;

class ImportPublicRPController extends Controller
{
    public function indexAction()
    {
        /*$form = $this->createForm(new RPType(),null,array(
		'translator_service' => $this->get('translator')
		));
		*/
        return $this->render('PedsAccountBundle:Default:import_public_rp_list.html.twig', array('form' => 1,));
    }
}