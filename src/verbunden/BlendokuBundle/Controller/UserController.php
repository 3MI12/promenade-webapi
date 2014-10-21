<?php

namespace verbunden\BlendokuBundle\Controller;

use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\RouteRedirectView;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;

use verbunden\BlendokuBundle\UserInterface;

/**
 * Rest controller for Blendoku
 *
 * @package verbunden\BlendokuBundle\Controller
 * @author Benjamin Brandt 2014
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
     *   }
     * )
     *
     * @author Benjamin Brandt
     * @version 1.0
     * @param string     $user_name
     * @return array
     */
    public function getShowAction($user_name) {
        if (!($user = $this->container->get('verbunden_blendoku.user.handler')->showUser($user_name))) {
            throw new NotFoundHttpException(sprintf('The resource \'%s\' was not found.', $user_name));
        }
        return $user;
    }

    /**
     * Login a user and get an accesskey. If user does not exist or authentification was not successfull it returns an empty accessKey. 
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Login/Register user",
     *   output = "verbunden\BlendokuBundle\Entity\User",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *    }
     * )
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @param Request               $request      the request object
     * @param ParamFetcherInterface $paramFetcher param fetcher service
     * @return array
     */
    public function postLoginAction(Request $request, ParamFetcherInterface $paramFetcher) {
        return $this->container->get('verbunden_blendoku.user.handler')->loginUser($request->request->all());
    }

    /**
     * Logout user 
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Logout a user and invalidate the accesskey.",
     *   output = "verbunden\BlendokuBundle\Entity\User",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *   }
     * )
     *
     * @author Benjamin Brandt
     * @version 1.0
     * @param Request               $request      the request object
     * @param ParamFetcherInterface $paramFetcher param fetcher service
     * @return array
     */
    public function postLogoutAction(Request $request, ParamFetcherInterface $paramFetcher) {
        return $accessKey = $this->container->get('verbunden_blendoku.user.handler')->logoutUser($request->request->all());
    }

}
