<?php

namespace verbunden\BlendokuBundle\Controller;

#use verbunden\BlendokuBundle\Form\NoteType;
use verbunden\BlendokuBundle\Entity\Level;
use verbunden\BlendokuBundle\Entity\Color;

use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\View\RouteRedirectView;
use FOS\RestBundle\View\View;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Rest controller for Blendoku
 *
 * @package verbunden\BlendokuBundle\Controller
 * @author Benjamin Brandt
 */
class GameController extends FOSRestController
{
	public function getNumberAction($id)
	{
	    return $this->getDoctrine()->getRepository('verbundenBlendokuBundle:Level')->findOneById($id);
	}
	
	public function getColorAction($id)
	{
	    return $this->getDoctrine()->getRepository('verbundenBlendokuBundle:Color')->findOneById($id);
	}
	
	public function getColorlistAction()
	{
	    return $this->getDoctrine()->getRepository('verbundenBlendokuBundle:Color')->findAll();
	}
	
	public function postNumberAction($id)
	{
	    return $this->container->get('doctrine.entity_manager')->getRepository('Level')->findById($id);
	}
	
	public function getListAction()
	{
	    return $this->container->get('doctrine.entity_manager')->getRepository('Level')->findAll();
	}
}
