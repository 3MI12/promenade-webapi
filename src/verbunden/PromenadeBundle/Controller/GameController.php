<?php

namespace verbunden\PromenadeBundle\Controller;

use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\RouteRedirectView;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use verbunden\PromenadeBundle\GameInterface;

/**
 * Rest controller for Promenade
 *
 * @package verbunden\PromenadeBundle\Controller
 * @author Benjamin Brandt
 */
class GameController extends FOSRestController {

    /**
     * List all level with score per user.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "List all level with score per user.",
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @Annotations\QueryParam(name="offset", requirements="\d+", nullable=true, description="Offset from which to start listing level.")
     * @Annotations\QueryParam(name="limit", requirements="\d+", default="15", description="How many level to return.")
     * 
     * @author Benjamin Brandt
     * @version 1.0
     * @param   string     $user_name    The name of the searched user.
     * @param   Request    $request      the request object
     * @param   ParamFetcherInterface $paramFetcher param fetcher service
     *
     * @return array list of searched level.
     */
    public function getListAction($user_name, Request $request, ParamFetcherInterface $paramFetcher) {
        $user_name = $request->headers->get('name');
        $accesstoken = $request->headers->get('accesstoken');
        $offset = $paramFetcher->get('offset');
        $offset = null == $offset ? 0 : $offset;
        $limit = $paramFetcher->get('limit');
        return $this->container->get('verbunden_Promenade.game.handler')->listGames($user_name, $accesstoken, $offset, $limit);
    }

    /**
     * Show level 
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Starts a Level for a given id",
     *   output = "verbunden\PromenadeBundle\Entity\Level",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *   }
     * )
     *
     * @author Benjamin Brandt
     * @version 1.0
     * @param int     $level_id      the level id
     * @return array
     */
    public function getShowAction($level_id) {
        return $level = $this->container->get('verbunden_Promenade.level.handler')->showLevel($level_id);
    }

    /**
     * Start Level 
     *
     * Example header for request:
     * 
     * "name": "benjamin"
     * "accesstoken": "a5cd71cac23047fd80cfca5a0eb1fe14"
     * 
     * Example response:
     * 
     * {
     *  "user": {
     *    "name": "benjamin"
     *  },
     *  "level": {
     *    "id": 1,
     *    "color": [
     *      "#aab835",
     *      "#7d9a38",
     *      "#4d7e38"
     *    ],
     *    "startgrid": {
     *     "52": {
     *        "color": "#d7da2e",
     *        "edit": false
     *      },
     *      "53": {
     *        "color": "#ffffff",
     *        "edit": true
     *      },
     *      "54": {
     *        "color": "#ffffff",
     *        "edit": true
     *      },
     *      "55": {
     *        "color": "#ffffff",
     *        "edit": true
     *      },
     *      "56": {
     *        "color": "#006836",
     *        "edit": false
     *      }
     *    },
     *    "complexity": 2
     *  },
     *  "starttime": 1414081381,
     *  "endtime": 1413936006,
     *  "score": 49
     * }
     * 
     * @ApiDoc(
     *   resource = true,
     *   description = "Starts a Level for a given id",
     *   output = "verbunden\PromenadeBundle\Entity\Level",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *   }
     * )
     *
     * @author Benjamin Brandt
     * @version 1.0
     * @param int     $level_id      the level id
     * @return array
     */
    public function getStartAction($level_id, Request $request, ParamFetcherInterface $paramFetcher) {
        $parameters['user']['name'] = $request->headers->get('name');
        $parameters['user']['accesstoken'] = $request->headers->get('accesstoken');
        $parameters['level']['id'] = $level_id;
        return $this->container->get('verbunden_Promenade.game.handler')->startGame($parameters);
    }

    /**
     * Solve level
     *
     * Example request:
     * 
     * {
     * "user": {
     * "name": "benjamin",
     * "accesstoken": "a5cd71cac23047fd80cfca5a0eb1fe14"
     * },
     * "grid": {
     *   "52": "#d7da2e",
     *   "53": "#aab835",
     *   "54": "#7d9a38",
     *   "55": "#4d7e38",
     *   "56": "#006836"
     * },
     * "complexity": "2",
     * "starttime": "1414081381"
     * }
     * 
     * Example response:
     * 
     * {
     *  "level_id": "1",
     *  "name": "benjamin",
     *  "error": "",
     *  "score": 50,
     *  "solved": true
     * }
     * 
     * @ApiDoc(
     *   resource = true,
     *   input = "verbunden\PromenadeBundle\Form\GameType",
     *   statusCodes = {
     *     204 = "Returned when successful",
     *   }
     * )
     * 
     * 
     * @author Benjamin Brandt
     * @version 1.0
     * @param Request               $request      the request object
     * @param ParamFetcherInterface $paramFetcher param fetcher service
     * @return array
     */
    public function postSolveAction($level_id, Request $request, ParamFetcherInterface $paramFetcher) {
        $parameters = $request->request->all();
        $parameters['user']['name'] = $request->headers->get('name');
        $parameters['user']['accesstoken'] = $request->headers->get('accesstoken');
        $parameters['level']['id'] = $level_id;
        return $this->container->get('verbunden_Promenade.game.handler')->solveGame($parameters);
    }

    /**
     * Create a level.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Creates a new page from the submitted data.",
     *   input = "verbunden\PromenadeBundle\Form\GameType",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *   }
     * )
     *
     * @param Request               $request      the request object
     * @return array
     */
    public function postCreateAction($level_id) {
        try {
            $newLevel = $this->container->get('verbunden_Promenade.game.handler')->post($request->request->all());
            $routeOptions = array('id' => $createLevel->getId(), '_format' => $request->get('_format'));
            return $this->routeRedirectView('api_1_get_page', $routeOptions, Codes::HTTP_CREATED);
        } catch (InvalidFormException $exception) {
            return $exception->getForm();
        }
    }

    /**
     * Create 15 default level.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Creates 15 default level in case no level is set yet.",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *   }
     * )
     *
     *
     * @Annotations\View(
     *  templateVar="level"
     * )
     *
     * @param Request               $request      the request object
     * @return array
     */
    public function getInitialAction(Request $request) {
        $levellist = array();
        for ($level_id = 1; $level_id < 16; $level_id++) {
            $parameters = array();
            $parameters['user']['name'] = $request->headers->get('name');
            $parameters['user']['accesstoken'] = $request->headers->get('accesstoken');
            switch ($level_id) {
                case 1:
                    $parameters['level']['id'] = $level_id;
                    $parameters["set"]["53"] = "#d7da2e";
                    $parameters["free"]["54"] = "#aab835";
                    $parameters["free"]["55"] = "#7d9a38";
                    $parameters["free"]["56"] = "#4d7e38";
                    $parameters["set"]["57"] = "#006836";
                    $parameters['complexity'] = '2';
                    break;
                case 2:
                    $parameters['level']['id'] = $level_id;
                    $parameters["set"]["52"] = "#f3f2f2";
                    $parameters["free"]["53"] = "#cbc9c8";
                    $parameters["free"]["54"] = "#a7a2a1";
                    $parameters["free"]["55"] = "#868180";
                    $parameters["free"]["56"] = "#676261";
                    $parameters["free"]["57"] = "#4c4948";
                    $parameters["set"]["58"] = "#333333";
                    $parameters['complexity'] = '3';
                    break;
                case 3:
                    $parameters['level']['id'] = $level_id;
                    $parameters["set"]["53"] = "#fe4232";
                    $parameters["free"]["54"] = "#be223f";
                    $parameters["free"]["55"] = "#932657";
                    $parameters["free"]["56"] = "#62276d";
                    $parameters["set"]["57"] = "#172983";
                    $parameters['complexity'] = '2';
                    break;
                case 4:
                    $parameters['level']['id'] = $level_id;
                    $parameters["set"]["64"] = "#009ee0";
                    $parameters["free"]["65"] = "#00adbe";
                    $parameters["free"]["66"] = "#70bd95";
                    $parameters["free"]["67"] = "#afcc5f";
                    $parameters["free"]["68"] = "#dfdb00";
                    $parameters["free"]["57"] = "#819686";
                    $parameters["free"]["47"] = "#706089";
                    $parameters["set"]["37"] = "#632181";
                    $parameters['complexity'] = '2';
                    break;
                case 5:
                    $parameters['level']['id'] = $level_id;
                    $parameters["set"]["64"] = "#d7da2e";
                    $parameters["free"]["65"] = "#d4d666";
                    $parameters["free"]["66"] = "#d2d38e";
                    $parameters["free"]["67"] = "#ced0b0";
                    $parameters["free"]["68"] = "#cccccb";
                    $parameters["free"]["58"] = "#a7c13a";
                    $parameters["free"]["48"] = "#6ea841";
                    $parameters["set"]["38"] = "#009345";
                    $parameters['complexity'] = '2';
                    break;
                case 6:
                    $parameters['level']['id'] = $level_id;
                    $parameters["free"]["44"] = "#f8b334";
                    $parameters["free"]["45"] = "#d2a769";
                    $parameters["free"]["46"] = "#a49b8d";
                    $parameters["free"]["47"] = "#6c8da9";
                    $parameters["set"]["48"] = "#007fc0";
                    $parameters["free"]["56"] = "#8f7554";
                    $parameters["set"]["64"] = "#00632e";
                    $parameters["free"]["65"] = "#4e602c";
                    $parameters["free"]["66"] = "#7a552a";
                    $parameters["free"]["67"] = "#9e4128";
                    $parameters["free"]["68"] = "#bd0926";
                    $parameters['complexity'] = '13';
                    break;
                case 7:
                    $parameters['level']['id'] = $level_id;
                    $parameters["set"]["52"] = "#c1272d";
                    $parameters["free"]["53"] = "#b46e59";
                    $parameters["free"]["54"] = "#9da197";
                    $parameters["set"]["55"] = "#6ec6d8";
                    $parameters["free"]["45"] = "#6bb0cd";
                    $parameters["free"]["35"] = "#689ac2";
                    $parameters["free"]["36"] = "#6782b4";
                    $parameters["free"]["37"] = "#6767a4";
                    $parameters["free"]["38"] = "#654d94";
                    $parameters["set"]["39"] = "#633287";
                    $parameters['complexity'] = '13';
                    break;
                case 8:
                    $parameters['level']['id'] = $level_id;
                    $parameters["set"]["64"] = "#ee869a";
                    $parameters["free"]["65"] = "#dc9e8d";
                    $parameters["free"]["66"] = "#c6ae73";
                    $parameters["free"]["67"] = "#acb74f";
                    $parameters["free"]["68"] = "#89ba17";
                    $parameters["free"]["55"] = "#79506f";
                    $parameters["set"]["45"] = "#13235b";
                    $parameters["free"]["57"] = "#44aca2";
                    $parameters["free"]["47"] = "#009ee0";
                    $parameters['complexity'] = '13';
                    break;
                case 9:
                    $parameters['level']['id'] = $level_id;
                    $parameters["set"]["45"] = "#47b393";
                    $parameters["free"]["46"] = "#87b05e";
                    $parameters["set"]["47"] = "#afac0b";
                    $parameters["set"]["65"] = "#e30732";
                    $parameters["free"]["66"] = "#991c4c";
                    $parameters["set"]["67"] = "#46245f";
                    $parameters["free"]["55"] = "#b58961";
                    $parameters["free"]["56"] = "#93755b";
                    $parameters["free"]["75"] = "#736256";
                    $parameters['complexity'] = '13';
                    break;
                case 10:
                    $parameters['level']['id'] = $level_id;
                    $parameters["free"]["63"] = "#733131";
                    $parameters["free"]["64"] = "#84453a";
                    $parameters["free"]["65"] = "#9c594a";
                    $parameters["free"]["66"] = "#b5715b";
                    $parameters["free"]["67"] = "#ce866b";
                    $parameters["set"]["68"] = "#e69e7b";
                    $parameters["free"]["53"] = "#3a1c31";
                    $parameters["free"]["54"] = "#5a395a";
                    $parameters["free"]["55"] = "#734d73";
                    $parameters["free"]["56"] = "#94659c";
                    $parameters["free"]["57"] = "#ad7db5";
                    $parameters["set"]["43"] = "#00043a";
                    $parameters["free"]["47"] = "#9475ff";
                    $parameters['complexity'] = '13';
                    break;
                case 11:
                    $parameters['level']['id'] = $level_id;
                    $parameters["set"]["22"] = "#efff10";
                    $parameters["free"]["23"] = "#c5d219";
                    $parameters["free"]["24"] = "#9cb221";
                    $parameters["free"]["25"] = "#849629";
                    $parameters["set"]["26"] = "#6b7931";
                    $parameters["free"]["27"] = "#526139";
                    $parameters["free"]["28"] = "#3a4931";
                    $parameters["free"]["29"] = "#313d42";
                    $parameters["set"]["30"] = "#192942";
                    $parameters["free"]["32"] = "#b5e311";
                    $parameters["free"]["36"] = "#6b713a";
                    $parameters["free"]["46"] = "#7b6d4a";
                    $parameters["free"]["42"] = "#84ca19";
                    $parameters["free"]["52"] = "#4ab221";
                    $parameters["free"]["35"] = "#947529";
                    $parameters["free"]["45"] = "#a45929";
                    $parameters["free"]["55"] = "#bd3d21";
                    $parameters["free"]["65"] = "#ce2021";
                    $parameters["free"]["37"] = "#4a6d4a";
                    $parameters["set"]["47"] = "#427d6b";
                    $parameters["free"]["57"] = "#3a8e84";
                    $parameters["free"]["67"] = "#319ea4";
                    $parameters["free"]["40"] = "#423d52";
                    $parameters["free"]["50"] = "#7c5d63";
                    $parameters["set"]["60"] = "#de8a8c";
                    $parameters['complexity'] = '25';
                    break;
                case 12:
                    $parameters['level']['id'] = $level_id;
                    $parameters["set"]["42"] = "#293d29";
                    $parameters["free"]["52"] = "#425131";
                    $parameters["free"]["62"] = "#4a653a";
                    $parameters["free"]["53"] = "#4a4531";
                    $parameters["free"]["54"] = "#633929";
                    $parameters["free"]["55"] = "#841c21";
                    $parameters["free"]["65"] = "#730421";
                    $parameters["free"]["45"] = "#943529";
                    $parameters["set"]["35"] = "#ad4d31";
                    $parameters["free"]["25"] = "#bd653a";
                    $parameters["free"]["36"] = "#945152";
                    $parameters["free"]["37"] = "#8c5584";
                    $parameters["free"]["38"] = "#7359ad";
                    $parameters["set"]["39"] = "#635dd6";
                    $parameters["free"]["49"] = "#6b699c";
                    $parameters["free"]["59"] = "#73756b";
                    $parameters["set"]["69"] = "#838631";
                    $parameters["free"]["70"] = "#8caa31";
                    $parameters["free"]["68"] = "#6b5d29";
                    $parameters["set"]["67"] = "#5a3d29";
                    $parameters['complexity'] = '25';
                    break;
                case 13:
                    $parameters['level']['id'] = $level_id;
                    $parameters["set"]["62"] = "#c58652";
                    $parameters["free"]["52"] = "#948183";
                    $parameters["free"]["42"] = "#6375ad";
                    $parameters["set"]["32"] = "#296dd6";
                    $parameters["free"]["33"] = "#4a86d6";
                    $parameters["free"]["34"] = "#6b9ace";
                    $parameters["set"]["35"] = "#8cb6ce";
                    $parameters["free"]["45"] = "#737d8c";
                    $parameters["free"]["55"] = "#5a5152";
                    $parameters["free"]["65"] = "#4a3121";
                    $parameters["free"]["75"] = "#421408";
                    $parameters["free"]["56"] = "#7b515a";
                    $parameters["free"]["57"] = "8c4c59";
                    $parameters["free"]["66"] = "#5a4129";
                    $parameters["free"]["76"] = "#423100";
                    $parameters["free"]["75"] = "#8c4d5a";
                    $parameters["free"]["67"] = "#6b5129";
                    $parameters["free"]["77"] = "#5a5910";
                    $parameters["free"]["58"] = "#a44d63";
                    $parameters["free"]["68"] = "#8c693a";
                    $parameters["set"]["78"] = "#6b8519";
                    $parameters["set"]["48"] = "#de0c8c";
                    $parameters['complexity'] = '25';
                    break;
                case 14:
                    $parameters['level']['id'] = $level_id;
                    $parameters["free"]["73"] = "#9c55ce";
                    $parameters["free"]["72"] = "#5a617c";
                    $parameters["set"]["71"] = "#197131";
                    $parameters["free"]["61"] = "#429229";
                    $parameters["free"]["51"] = "#6bb229";
                    $parameters["free"]["41"] = "#94d229";
                    $parameters["free"]["42"] = "#7bdb5b";
                    $parameters["free"]["43"] = "#6be794";
                    $parameters["free"]["44"] = "#52efc5";
                    $parameters["set"]["45"] = "#42f7ff";
                    $parameters["free"]["55"] = "#73dbc5";
                    $parameters["free"]["65"] = "#a4ba84";
                    $parameters["set"]["75"] = "#d6a252";
                    $parameters["free"]["56"] = "#7bc2ad";
                    $parameters["free"]["66"] = "#949e73";
                    $parameters["free"]["76"] = "#b5814a";
                    $parameters["free"]["57"] = "#8cb29c";
                    $parameters["free"]["67"] = "#8c8a6b";
                    $parameters["free"]["77"] = "#8c613a";
                    $parameters["free"]["58"] = "#8c9a84";
                    $parameters["free"]["68"] = "#837563";
                    $parameters["free"]["78"] = "#837563";
                    $parameters["free"]["79"] = "#422029";
                    $parameters["free"]["69"] = "#735552";
                    $parameters["free"]["59"] = "#94866b";
                    $parameters["free"]["49"] = "#c5be9c";
                    $parameters["set"]["37"] = "#b53563";
                    $parameters["free"]["27"] = "#a4558c";
                    $parameters["free"]["17"] = "#9471b5";
                    $parameters["set"]["7"] = "#8c96e6";
                    $parameters["set"]["39"] = "#e6efbd";
                    $parameters["free"]["38"] = "#ce928c";
                    $parameters['complexity'] = '25';
                    break;
                case 15:
                    $parameters['level']['id'] = $level_id;
                    $parameters["free"]["21"] = "#2108b5";
                    $parameters["free"]["22"] = "#3a24b5";
                    $parameters["free"]["23"] = "#523db5";
                    $parameters["free"]["24"] = "#6b55b5";
                    $parameters["free"]["25"] = "#8c6db5";
                    $parameters["free"]["26"] = "#a486bd";
                    $parameters["free"]["27"] = "#c5a2bd";
                    $parameters["set"]["28"] = "#debebd";
                    $parameters["free"]["38"] = "#adb2b5";
                    $parameters["free"]["48"] = "#7caebd";
                    $parameters["free"]["58"] = "#52a6b5";
                    $parameters["set"]["68"] = "#21a2bd";
                    $parameters["free"]["69"] = "#398e84";
                    $parameters["set"]["70"] = "#42714a";
                    $parameters["free"]["60"] = "#31613a";
                    $parameters["free"]["50"] = "#214d21";
                    $parameters["set"]["40"] = "#113910";
                    $parameters["free"]["47"] = "#7bb283";
                    $parameters["free"]["46"] = "#73b242";
                    $parameters["free"]["56"] = "#8c9e42";
                    $parameters["set"]["66"] = "#ad8a4a";
                    $parameters["free"]["65"] = "#9c6142";
                    $parameters["set"]["64"] = "#944142";
                    $parameters['complexity'] = '25';
                    break;
            }
            array_push($levellist, $this->container->get('verbunden_Promenade.level.handler')->createLevel($parameters));
        }
        return $levellist;
    }

}
