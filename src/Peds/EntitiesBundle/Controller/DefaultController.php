<?php

namespace Peds\EntitiesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('PedsEntitiesBundle:Default:index.html.twig', array('name' => $name));
    }
}
