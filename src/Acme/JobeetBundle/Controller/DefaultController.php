<?php

namespace Acme\JobeetBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('AcmeJobeetBundle:Default:index.html.twig', array('name' => $name));
    }
}
