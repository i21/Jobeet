<?php

namespace Acme\JobeetBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        $format = $this->getRequest()->getRequestFormat();
        return $this->render('AcmeJobeetBundle:Default:index.'.$format.'.twig', array('name' => $name));
    }

    public function loginAction(){
    	$request = $this->getRequest();
    	$session = $request->getSession();

    	// Get the login error if there is one
    	if($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR))
    	{
    		$error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
    	} else {
    		$error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
    		$session->remove(SecurityContext::AUTHENTICATION_ERROR);
    	}

    	return $this->render('AcmeJobeetBundle:Default:login.html.twig', array(
    		// Last username entered by the user
    		'last_username' => $session->get(SecurityContext::LAST_USERNAME),
    		'error' => $error));
    }



}
