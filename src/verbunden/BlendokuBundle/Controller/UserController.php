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
use verbunden\BlendokuBundle\Form\UserType;
use verbunden\BlendokuBundle\UserInterface;

/**
 * Rest controller for Blendoku
 *
 * @package verbunden\BlendokuBundle\Controller
 * @author Benjamin Brandt
 */
class UserController extends FOSRestController {

    /**
     * Show user Object
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Login a user and get an accesskey.",
     *   output = "verbunden\BlendokuBundle\Entity\User",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the user is not found"
     *   }
     * )
     *
     * @author Benjamin Brandt
     * @version 1.0
     * @param int     $user_name      the user name
     * @return array
     * @throws NotFoundHttpException when page not exist
     */
    public function getShowAction($user_name) {
        if (!($user = $this->container->get('verbunden_blendoku.user.handler')->showUser($user_name))) {
            throw new NotFoundHttpException(sprintf('The resource \'%s\' was not found.', $user_name));
        }
        return $user;
    }

    /**
     * Login user 
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Login a user and get an accesskey. If user does not exist or authentification was not successfull it returns an empty accessKey",
     *   input = "verbunden\BlendokuBundle\Form\UserType",
     *   output = "verbunden\BlendokuBundle\Entity\User",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *    }
     * )
     * @author Martin Kuntizsch 2014
     * @version 1.0
     * @param Request               $request      the request object
     * @param ParamFetcherInterface $paramFetcher param fetcher service     
     * @return array
     */
    public function postLoginAction(Request $request, ParamFetcherInterface $paramFetcher) {
        $accessKey = $this->container->get('verbunden_blendoku.user.handler')->loginUser($request->request->all());
        return array("accessKey" => $accessKey);
    }

    /**
     * Logout user 
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Logout a user and invalidate the accesskey.",
     *   input = "verbunden\BlendokuBundle\Form\UserType",
     *   output = "verbunden\BlendokuBundle\Entity\User",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the user is not found"
     *   }
     * )
     *
     * @author Benjamin Brandt
     * @version 1.0
     * @param int     $level_id      the level id
     * @return array
     * @throws NotFoundHttpException when page not exist
     */
    public function postLogoutAction(Request $request, ParamFetcherInterface $paramFetcher) {
        $accessKey = $this->container->get('verbunden_blendoku.user.handler')->loginUser($request->request->all());
        return array("accessKey" => $accessKey);
    }

}
