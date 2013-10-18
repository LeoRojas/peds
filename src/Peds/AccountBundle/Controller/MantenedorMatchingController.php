<?php

namespace Peds\AccountBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Peds\AccountBundle\Form\MatchingType;
use Peds\EntitiesBundle\Entity\Matching;

class MantenedorMatchingController extends Controller
{
    public function indexAction()
    {
    	$em = $this->getDoctrine()->getEntityManager();
    	$matching=$em->getRepository('PedsEntitiesBundle:Matching')->findAll();
        return $this->render('PedsAccountBundle:Default:mantenedor_matching.html.twig',array('matching' =>$matching));
    }
    public function editAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $defaultData = array('');
        $matching = $em->getRepository('PedsEntitiesBundle:Matching')->find($id);
        if (!$matching) {
            throw $this->createNotFoundException(
                'No matching found for id '.$id
            );
        }
        //$matching = new Matching();
        $form = $this->createForm(new MatchingType(), $matching);

        if ($this->getRequest()->isMethod('POST')) {
        $form->bind($this->getRequest());

            if ($form->isValid()) {
            print_r($_POST);
            $matching = $form->getData();
            $em->persist($matching);
            $em->flush();
            print_r("Matching entry updated");
            //$this->get('session')->setFlash('notice', 'Depósito Realizado!');
            

            }//isvalid
        }
    	$em = $this->getDoctrine()->getEntityManager();
    	$matching=$em->getRepository('PedsEntitiesBundle:Matching')->findAll();
        return $this->render('PedsAccountBundle:Default:mantenedor_matching_edit.html.twig',array('form' => $form->createView(),'matching' =>$matching, 'id' => $id ));
    }

}