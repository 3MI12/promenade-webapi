<?php

namespace verbunden\BlendokuBundle\Controller;

#use verbunden\BlendokuBundle\Form\NoteType;
use verbunden\BlendokuBundle\Entity\Game;

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
class StatsController extends FOSRestController
{	
	public function getHighscoreAction()
	{
	    return $this->container->get('doctrine.entity_manager')->getRepository('Game')->findAll();
	}
	
	public function getUserscoreAction()
	{
	    return $this->container->get('doctrine.entity_manager')->getRepository('Game')->findAll();
	}
	
	public function getLevelscoreAction()
	{
	    return $this->container->get('doctrine.entity_manager')->getRepository('Game')->findAll();
	}
}
