<?php

namespace verbunden\PromenadeBundle\Controller;

use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\RouteRedirectView;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;

use verbunden\PromenadeBundle\GameInterface;

/**
 * Rest controller for Promenade
 *
 * @package verbunden\PromenadeBundle\Controller
 * @author Benjamin Brandt
 */
class StatsController extends FOSRestController {

    /**
     * Get global highscore.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Highscore",
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @Annotations\QueryParam(name="offset", requirements="\d+", nullable=true, description="Offset from which to start listing level.")
     * @Annotations\QueryParam(name="limit", requirements="\d+", default="10", description="How many users to return.")
     *
     * @author Benjamin Brandt
     * @param Request               $request      the request object
     * @param ParamFetcherInterface $paramFetcher param fetcher service
     * @return array
     */
    public function getHighscoreAction(Request $request, ParamFetcherInterface $paramFetcher) {
        $offset = $paramFetcher->get('offset');
        $offset = null == $offset ? 0 : $offset;
        $limit = $paramFetcher->get('limit');
        return $this->container->get('verbunden_Promenade.game.handler')->HighScore($limit, $offset);
    }

    /**
     * Get score of a User
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Userscore",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *   }
     * )
     * 
     * @author Benjamin Brandt
     * @version 1.0
     * @param Request $request the request object
     * @return array
     */
    public function getUserscoreAction($user_name) {
        return $this->container->get('verbunden_Promenade.game.handler')->UserScore($user_name);
    }

}
