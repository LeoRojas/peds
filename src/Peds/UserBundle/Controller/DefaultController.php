<?php

namespace Peds\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('PedsUserBundle:Default:index.html.twig', array('name' => $name));
    }
}
