<?php

namespace verbunden\BlendokuBundle\Controller;

use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\RouteRedirectView;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;

use verbunden\BlendokuBundle\GameInterface;

/**
 * Rest controller for Blendoku
 *
 * @package verbunden\BlendokuBundle\Controller
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
        return $this->container->get('verbunden_blendoku.game.handler')->listGames($user_name, $accesstoken, $offset, $limit);
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
     *   }
     * )
     *
     * @author Benjamin Brandt
     * @version 1.0
     * @param int     $level_id      the level id
     * @return array
     */
    public function getShowAction($level_id) {
        return $level = $this->container->get('verbunden_blendoku.level.handler')->showLevel($level_id);
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
     *}
     * 
     * @ApiDoc(
     *   resource = true,
     *   description = "Starts a Level for a given id",
     *   output = "verbunden\BlendokuBundle\Entity\Level",
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
        return $this->container->get('verbunden_blendoku.game.handler')->startGame($parameters);
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
     *   input = "verbunden\BlendokuBundle\Form\GameType",
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
        return $this->container->get('verbunden_blendoku.game.handler')->solveGame($parameters);
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
     *   }
     * )
     *
     * @param Request               $request      the request object
     * @return array
     */
    public function postCreateAction($level_id) {
        try {
            $newLevel = $this->container->get('verbunden_blendoku.game.handler')->post($request->request->all());
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
    public function getInitialAction() {
        $levellist = array();
        for ($level_id = 1; $level_id < 16; $level_id++) {
            $parameters = array();
            switch ($level_id) {
                case 1:
                    $parameters['level']['id'] = $level_id;
                    $parameters['set']['52'] = '#d7da2e';
                    $parameters['free']['53'] = '#aab835';
                    $parameters['free']['54'] = '#7d9a38';
                    $parameters['free']['55'] = '#4d7e38';
                    $parameters['set']['56'] = '#006836';
                    $parameters['complexity'] = '2';
                    break;
                case 2:
                    $parameters['level']['id'] = $level_id;
                    $parameters['set']['51'] = '#c0c0c0';
                    $parameters['free']['52'] = '#cbc9c8';
                    $parameters['free']['53'] = '#a7a2a1';
                    $parameters['free']['54'] = '#868180';
                    $parameters['free']['55'] = '#676261';
                    $parameters['set']['56'] = '#4c4948';
                    $parameters['free']['57'] = '#333333';
                    $parameters['complexity'] = '3';
                    break;
                case 3:
                    $parameters['level']['id'] = $level_id;
                    $parameters['set']['43'] = '#fe42322';
                    $parameters['free']['44'] = '#be223f';
                    $parameters['free']['45'] = '#932657';
                    $parameters['free']['46'] = '#62276d';
                    $parameters['set']['47'] = '#172983';
                    $parameters['complexity'] = '2';
                    break;
                case 4:
                    $parameters['level']['id'] = $level_id;
                    $parameters['set']['63'] = '#009ee0';
                    $parameters['free']['64'] = '#00adbe';
                    $parameters['free']['65'] = '#70bd95';
                    $parameters['free']['66'] = '#afcc5f';
                    $parameters['free']['67'] = '#dfdb00';
                    $parameters['free']['56'] = '#819686';
                    $parameters['free']['46'] = '#706089';
                    $parameters['set']['36'] = '#632181';
                    $parameters['complexity'] = '2';
                    break;
                case 5:
                    $parameters['level']['id'] = $level_id;
                    $parameters['set']['63'] = '#d7da2e';
                    $parameters['free']['64'] = '#d4d666';
                    $parameters['free']['65'] = '#d2d38e';
                    $parameters['free']['66'] = '#ced0b0';
                    $parameters['free']['67'] = '#cccccb';
                    $parameters['free']['57'] = '#a7c13a';
                    $parameters['free']['47'] = '#6ea841';
                    $parameters['set']['37'] = '#009345';
                    $parameters['complexity'] = '2';
                    break;
                case 6:
                    $parameters['level']['id'] = $level_id;
                    $parameters['set']['51'] = '#c1272d';
                    $parameters['free']['52'] = '#b46e59';
                    $parameters['free']['53'] = '#9da197';
                    $parameters['set']['43'] = '#6ec6d8';
                    $parameters['free']['33'] = '#6bb1ce';
                    $parameters['free']['34'] = '#689ac2';
                    $parameters['free']['35'] = '#6782b4';
                    $parameters['free']['36'] = '#6767a4';
                    $parameters['free']['37'] = '#654d94';
                    $parameters['set']['38'] = '#633287';
                    $parameters['complexity'] = '13';
                    break;
                case 7:
                    $parameters['level']['id'] = $level_id;
                    $parameters['free']['63'] = '#f8b334';
                    $parameters['free']['44'] = '#d2a868';
                    $parameters['free']['45'] = '#a49b8d';
                    $parameters['free']['46'] = '#6b8da9';
                    $parameters['set']['47'] = '#007fc0';
                    $parameters['free']['55'] = '#8f7554';
                    $parameters['set']['63'] = '#00632e';
                    $parameters['free']['64'] = '#4e602c';
                    $parameters['free']['65'] = '#7a552a';
                    $parameters['free']['66'] = '#9e4128';
                    $parameters['free']['67'] = '#bd0a26';
                    $parameters['complexity'] = '13';
                    break;
                case 8:
                    $parameters['level']['id'] = $level_id;
                    $parameters['set']['63'] = '#ee869a';
                    $parameters['free']['64'] = '#dc9e8d';
                    $parameters['free']['65'] = '#c6ae73';
                    $parameters['free']['66'] = '#acb74f';
                    $parameters['free']['67'] = '#89ba17';
                    $parameters['set']['56'] = '#44aca2';
                    $parameters['free']['46'] = '#009ee0';
                    $parameters['complexity'] = '13';
                    break;
                case 9:
                    $parameters['level']['id'] = $level_id;
                    $parameters['set']['44'] = '#47b393';
                    $parameters['free']['45'] = '#87b05e';
                    $parameters['set']['46'] = '#afac0b';
                    $parameters['free']['54'] = '#b58961';
                    $parameters['free']['55'] = '#93755b';
                    $parameters['free']['56'] = '#736256';
                    $parameters['set']['64'] = '#e30732';
                    $parameters['free']['65'] = '#991c4c';
                    $parameters['set']['66'] = '#46245f';
                    $parameters['complexity'] = '13';
                    break;
                case 10:
                    $parameters['level']['id'] = $level_id;
                    $parameters['set']['42'] = '#0e1634';
                    $parameters['free']['52'] = '#3a1b31';
                    $parameters['set']['62'] = '#733031';
                    $parameters['free']['53'] = '#542d49';
                    $parameters['free']['54'] = '#714266';
                    $parameters['free']['55'] = '#915c87';
                    $parameters['free']['56'] = '#b47baf';
                    $parameters['free']['63'] = '#86403c';
                    $parameters['free']['64'] = '#9c5449';
                    $parameters['free']['65'] = '#b36a58';
                    $parameters['free']['66'] = '#cc8369';
                    $parameters['set']['67'] = '#e69f7c';
                    $parameters['complexity'] = '13';
                    break;
                case 11:
                    $parameters['level']['id'] = $level_id;
                    $parameters['set']['21'] = '#e7df00';
                    $parameters['free']['31'] = '#bfce02';
                    $parameters['free']['41'] = '#91bd1d';
                    $parameters['free']['51'] = '#58ab2b';
                    $parameters['free']['22'] = '#c8c12d';
                    $parameters['free']['23'] = '#aba640';
                    $parameters['free']['24'] = '#8f8c49';
                    $parameters['free']['34'] = '#a17e3d';
                    $parameters['free']['44'] = '#b16831';
                    $parameters['free']['54'] = '#bf4d27';
                    $parameters['free']['64'] = '#cd1f20';
                    $parameters['set']['25'] = '#76754d';
                    $parameters['free']['35'] = '#6c703a';
                    $parameters['free']['45'] = '#736d49';
                    $parameters['set']['26'] = '#5e5f4c';
                    $parameters['free']['36'] = '#50704f';
                    $parameters['set']['46'] = '#4c7f69';
                    $parameters['free']['56'] = '#439085';
                    $parameters['free']['66'] = '#2fa1a5';
                    $parameters['set']['28'] = '#484c4a';
                    $parameters['free']['29'] = '#222d42';
                    $parameters['free']['39'] = '#5c4457';
                    $parameters['free']['49'] = '#99626f';
                    $parameters['set']['59'] = '#de8a8b';
                    $parameters['complexity'] = '25';
                    break;
                case 12:
                    $parameters['level']['id'] = $level_id;
                    $parameters['set']['51'] = '#293d29';
                    $parameters['free']['61'] = '#395032';
                    $parameters['free']['71'] = '#4a6439';
                    $parameters['free']['62'] = '#534931';
                    $parameters['free']['63'] = '#623929';
                    $parameters['free']['74'] = '#73131c';
                    $parameters['free']['64'] = '#8f1e28';
                    $parameters['free']['54'] = '#953429';
                    $parameters['free']['44'] = '#a44d28';
                    $parameters['set']['34'] = '#be6539';
                    $parameters['free']['35'] = '#95505a';
                    $parameters['free']['36'] = '#8c5481';
                    $parameters['free']['37'] = '#71599e';
                    $parameters['set']['38'] = '#5d5aa0';
                    $parameters['set']['48'] = '#696da5';
                    $parameters['free']['58'] = '#737462';
                    $parameters['set']['68'] = '#5a3d2a';
                    $parameters['free']['66'] = '#6b6129';
                    $parameters['free']['67'] = '#7c8630';
                    $parameters['set']['69'] = '#94ae39';
                    $parameters['complexity'] = '25';
                    break;
                case 13:
                    $parameters['level']['id'] = $level_id;
                    $parameters['set']['31'] = '#296dd6';
                    $parameters['set']['61'] = '#c58652';
                    $parameters['set']['56'] = '#8c4d5a';
                    $parameters['set']['47'] = '#de0c8c';
                    $parameters['set']['34'] = '#8cb6ce';
                    $parameters['set']['77'] = '#6b8519'; 
                    $parameters['free']['41'] = '#6375ad';
                    $parameters['free']['51'] = '#948183';
                    $parameters['free']['32'] = '#4a86d6';
                    $parameters['free']['33'] = '#6b9ace';
                    $parameters['free']['44'] = '#737d8c';
                    $parameters['free']['54'] = '#5a5152';
                    $parameters['free']['64'] = '#4a3121';
                    $parameters['free']['74'] = '#421408'; 
                    $parameters['free']['55'] = '#7b515a';
                    $parameters['free']['65'] = '#5a4129';
                    $parameters['free']['75'] = '#423100';
                    $parameters['free']['66'] = '#6b5129';
                    $parameters['free']['67'] = '#6a5129';
                    $parameters['free']['76'] = '#5a5910';
                    $parameters['free']['47'] = '#d52183';
                    $parameters['free']['57'] = '#a44d63';
                    $parameters['free']['67'] = '#8c693a';
                    $parameters['complexity'] = '25';
                    break;
                case 14:
                    $parameters['level']['id'] = $level_id;
                    $parameters['free']['40'] = '#96bf33';
                    $parameters['free']['50'] = '#6db129';
                    $parameters['free']['60'] = '#41932c';
                    $parameters['set']['70'] = '#187131';
                    $parameters['free']['71'] = '#5c637d';
                    $parameters['set']['72'] = '#865a9d';
                    $parameters['free']['41'] = '#83bc5a';
                    $parameters['free']['42'] = '#7fbf88';
                    $parameters['free']['43'] = '#79c3af';
                    $parameters['set']['44'] = '#79cadd';
                    $parameters['free']['54'] = '#7ec6bb';
                    $parameters['free']['64'] = '#a4ba83';
                    $parameters['set']['74'] = '#d4a150';
                    $parameters['free']['55'] = '#7ac2ac';
                    $parameters['free']['65'] = '#949e74';
                    $parameters['free']['75'] = '#b6824a';
                    $parameters['free']['56'] = '#8ab19c';
                    $parameters['free']['66'] = '#8d8b6c';
                    $parameters['free']['67'] = '#8d6139';
                    $parameters['free']['57'] = '#8e9b84';
                    $parameters['free']['67'] = '#837563';
                    $parameters['free']['77'] = '#6b4139';
                    $parameters['set']['38'] = '#e6ecbd';
                    $parameters['free']['48'] = '#c5be9c';
                    $parameters['free']['58'] = '#94886d';
                    $parameters['free']['68'] = '#735552';
                    $parameters['free']['37'] = '#cd918b';
                    $parameters['set']['36'] = '#b33362';
                    $parameters['free']['26'] = '#a4548c';
                    $parameters['free']['16'] = '#9471ab';
                    $parameters['set']['06'] = '#8e93c4';
                    $parameters['complexity'] = '25';
                    break;
                case 15:
                    $parameters['level']['id'] = $level_id;
                    $parameters['free']['20'] = '#34388c';
                    $parameters['free']['21'] = '#3e3784';
                    $parameters['free']['22'] = '#504593';
                    $parameters['free']['23'] = '#67559c';
                    $parameters['free']['24'] = '#8b6ca8';
                    $parameters['free']['25'] = '#a486b9';
                    $parameters['free']['26'] = '#c4a2c9';
                    $parameters['set']['27'] = '#debdbc';
                    $parameters['free']['37'] = '#adb3b6';
                    $parameters['free']['47'] = '#7cadbb';
                    $parameters['free']['57'] = '#52a6b5';
                    $parameters['free']['67'] = '#23a3bd';
                    $parameters['free']['68'] = '#3c8f84';
                    $parameters['set']['69'] = '#44714a';
                    $parameters['free']['59'] = '#32613a';
                    $parameters['free']['49'] = '#234d20';
                    $parameters['set']['39'] = '#133918';
                    $parameters['complexity'] = '25';
                    break;
            }
            array_push($levellist, $this->container->get('verbunden_blendoku.level.handler')->createLevel($parameters));
        }
        return $levellist;
    }

}
