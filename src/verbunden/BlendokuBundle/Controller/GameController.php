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
	public function getInitAction(){	
		$color['23'] = '#c2c2c2';
		$free['24'] = '#';
		$free['25'] = '#';
		$free['26'] = '#';
		$free['27'] = '#';
		$free['28'] = '#';
		$color['29'] = '#c4c4c4';
		$game['color']=array('#12341','#12541','#12361','#21341','#52341');

		for ($i = 0; $i <= 99; $i++) {
	    	if (isset($free[$i])){
				$game['grid'][$i]=array('color' => '#6b6b6b','edit' => true);
			}elseif(isset($color[$i])){
				$game['grid'][$i]=array('color' => $color[$i],'edit' => false);
			}
		}
				
		$level = new Level();
		$level->setColor($game['color']);	
		$level->setComplexity('3');
		$level->setGrid($game['grid']);
		$level->setStartgrid($game['grid']);		
		$em = $this->getDoctrine()->getManager();
		$em->persist($level);
		$em->flush();
	    return $level;
	}
	
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
