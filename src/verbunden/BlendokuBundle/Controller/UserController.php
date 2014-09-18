<?php

namespace verbunden\BlendokuBundle\Controller;

#use verbunden\BlendokuBundle\Form\NoteType;
use verbunden\BlendokuBundle\Entity\User;

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
class UserController extends FOSRestController
{	
	public function getShowAction($id)
	{
	    $reguest = $this->getDoctrine()->getRepository('verbundenBlendokuBundle:User')->findById($id);
		$data = array($reguest->name);
		return $reguest;
	}
	
	public function postShowAction($id)
	{
	    return $this->getDoctrine()->getRepository('verbundenBlendokuBundle:User')->findById($id);
	}
	
	/**
     * List all users.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @Annotations\QueryParam(name="offset", requirements="\d+", nullable=true, description="Offset from which to start listing users.")
     * @Annotations\QueryParam(name="limit", requirements="\d+", default="5", description="How many users to return.")
     *
     * @Annotations\View(
     *  templateVar="user"
     * )
     *
     * @param Request               $request      the request object
     * @param ParamFetcherInterface $paramFetcher param fetcher service
     *
     * @return array
     */
	public function getListAction()
	{
		$request = $this->getDoctrine()->getRepository('verbundenBlendokuBundle:User')->findAll();
	    $data = array();
		return $request;
	}
}
