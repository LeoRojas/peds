<?php

namespace Peds\CompareBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ChartController extends Controller
{
    public function indexAction()
    {
        return $this->render('PedsCompareBundle:Default:chart.html.twig');
    }
}
