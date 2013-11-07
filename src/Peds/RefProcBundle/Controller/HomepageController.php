<?php

namespace Peds\RefProcBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomepageController extends Controller
{
    public function indexAction()
    {
		return $this->redirect($this->generateUrl('login'), 301);
        //return $this->render('PedsRefProcBundle:Default:homepage.html.twig');
    }
}
