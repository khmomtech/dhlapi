<?php

namespace Wk\DhlApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('WkDhlApiBundle:Default:index.html.twig', array('name' => $name));
    }
}
