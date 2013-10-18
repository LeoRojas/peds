<?php

namespace Peds\CompareBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('PedsCompareBundle:Default:index.html.twig', array('name' => $name));
    }
}
