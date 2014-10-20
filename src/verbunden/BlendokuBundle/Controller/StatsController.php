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
use verbunden\BlendokuBundle\GameInterface;

/**
 * Rest controller for Blendoku
 *
 * @package verbunden\BlendokuBundle\Controller
 * @author Benjamin Brandt
 */
class StatsController extends FOSRestController {

    /**
     * Get global highscore.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @Annotations\QueryParam(name="offset", requirements="\d+", nullable=true, description="Offset from which to start listing level.")
     * @Annotations\QueryParam(name="limit", requirements="\d+", default="10", description="How many users to return.")
     *
     * @Annotations\View(
     *  templateVar="level"
     * )
     *
     * @param Request               $request      the request object
     * @param ParamFetcherInterface $paramFetcher param fetcher service
     *
     * @return array
     */
    public function getHighscoreAction() {
        return $this->container->get('verbunden_blendoku.game.handler')->calculateHighScore();
    }

    /**
     * Get the Score of a User
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     204 = "Returned when successful",
     *     400 = "Returned when the request has errors"
     *   }
     * )
     * @author Benjamin Brandt
     * @version 1.0
     * @param Request               $request      the request object
     * @return array
     */
    public function getUserscoreAction($user_name) {
        return $this->container->get('verbunden_blendoku.game.handler')->calculateUserScore($user_name);
    }

}
