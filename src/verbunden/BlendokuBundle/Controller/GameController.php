<?php
namespace verbunden\BlendokuBundle\Controller;
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

use verbunden\BlendokuBundle\Exception\InvalidFormException;
use verbunden\BlendokuBundle\Form\GameType;
use verbunden\BlendokuBundle\GameInterface;

/**
 * Rest controller for Blendoku
 *
 * @package verbunden\BlendokuBundle\Controller
 * @author Benjamin Brandt
 */
class GameController extends FOSRestController
{
	/**
     * List all level.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @Annotations\QueryParam(name="offset", requirements="\d+", nullable=true, description="Offset from which to start listing level.")
     * @Annotations\QueryParam(name="limit", requirements="\d+", default="5", description="How many level to return.")
     *
	 * @author Benjamin Brandt
	 * @version 1.0
     * @param Request               $request      the request object
     * @param ParamFetcherInterface $paramFetcher param fetcher service
     *
     * @return array
     */
    public function getListAction(Request $request, ParamFetcherInterface $paramFetcher){
        $offset = $paramFetcher->get('offset');
        $offset = null == $offset ? 0 : $offset;
        $limit = $paramFetcher->get('limit');
        return $this->container->get('verbunden_blendoku.level.handler')->listLevel($limit, $offset);
    }
	
	/**
     * Show level 
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Starts a Level for a given id",
     *   output = "verbunden\BlendokuBundle\Entity\Level",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the level is not found"
     *   }
     * )
     *
	 * @author Benjamin Brandt
	 * @version 1.0
     * @param int     $id      the level id
     * @return array
     * @throws NotFoundHttpException when page not exist
     */
	public function getNumberShowAction($level_id)
	{
		if (!($level = $this->container->get('verbunden_blendoku.level.handler')->showLevel($level_id))) {
            throw new NotFoundHttpException(sprintf('The resource \'%s\' was not found.',$level_id));
        }
        return $level;
	}
	
	/**
     * Start Level 
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Starts a Level for a given id",
     *   output = "verbunden\BlendokuBundle\Entity\Level",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the level is not found"
     *   }
     * )
     *
	 * @author Benjamin Brandt
	 * @version 1.0
     * @param int     $id      the level id
     * @return array
     * @throws NotFoundHttpException when page not exist
     */
	public function getNumberStartAction($level_id)
	{
		return $this->getLevelOr404($level_id);
	}
	
	/**
     * Solve level
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "verbunden\BlendokuBundle\Form\GameType",
     *   statusCodes = {
     *     204 = "Returned when successful",
     *     400 = "Returned when the request has errors"
     *   }
     * )
     * @author Benjamin Brandt
	 * @version 1.0
     * @param Request               $request      the request object
     * @param ParamFetcherInterface $paramFetcher param fetcher service
     * @return FormTypeInterface|View
	 * @throws NotFoundHttpException when page not exist
     */
	public function postNumberSolveAction($level_id)
	{
		if (!($bool = $this->container->get('verbunden_blendoku.level.handler')->solveLevel($level_id))) {
            throw new NotFoundHttpException(sprintf('The resource \'%s\' was not found.',$level_id));
        }
		return $bool;
	}
	
	/**
     * Create a level.
     *
     * @ApiDoc(
     *   resource = true,
	 *   description = "Creates a new page from the submitted data.",
	 *   input = "verbunden\BlendokuBundle\Form\GameType",
     *   statusCodes = {
     *     200 = "Returned when successful",
	 *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     *
     * @Annotations\View(
     *  templateVar="level"
     * )
     *
     * @param Request               $request      the request object
     * @return FormTypeInterface|View
     */
	public function postNumberCreateAction($level_id){
		try {
			$newLevel = $this->container->get('verbunden_blendoku.game.handler')->post($request->request->all());
			$routeOptions = array('id' => $createLevel->getId(),'_format' => $request->get('_format'));
			return $this->routeRedirectView('api_1_get_page', $routeOptions, Codes::HTTP_CREATED);
		} catch (InvalidFormException $exception) {
			return $exception->getForm();
		}
	}
	
	public function getTestCreateAction($level_id){
	    switch ($level_id) {
		    case 1:
				$parameters['level_id'] = $level_id;
		        $parameters['set']['52'] = '#d7da2e';
				$parameters['free']['53'] = '#aab835';
				$parameters['free']['54'] = '#7d9a38';
				$parameters['free']['55'] = '#4d7e38';
				$parameters['set']['56'] = '#006836';
				$parameters['complexity'] = '2';
		        break;
		    case 2:
				$parameters['level_id'] = $level_id;
				$parameters['set']['51'] = '#f3f2f2';
				$parameters['free']['52'] = '#cbc9c8';
				$parameters['free']['53'] = '#a7a2a1';
				$parameters['free']['54'] = '#868180';
				$parameters['free']['55'] = '#676261';
				$parameters['set']['56'] = '#4c4948';
				$parameters['free']['57'] = '#333333';
				$parameters['complexity'] = '3';
		        break;	
		}
		return $this->container->get('verbunden_blendoku.level.handler')->createLevel($parameters);		
	}
}