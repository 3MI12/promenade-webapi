<?php

namespace verbunden\BlendokuBundle\Handler;

use verbunden\BlendokuBundle\Model\LevelInterface;
use verbunden\BlendokuBundle\Model\GameInterface;

/**
 * GameHandlerInterface
 *
 * @package verbunden\BlendokuBundle\Handler
 * @author Benjamin Brandt 2014
 * @version 1.0
 */
interface GameHandlerInterface {

    /**
     * List level with userscore
     *
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @param string $username
     * @param string $accesstoken
     * @param string $limit
     * @param string $offset
     * @return array queryresult
     */
    public function listGames($username, $accesstoken, $offset, $limit = 15);

    /**
     * Start a level
     *
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @param array $parameters
     * @return array game object
     */
    public function startGame(array $parameters);

    /**
     * Solve a level
     *
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @param array $parameters
     * @return array status
     */
    public function solveGame(array $parameters);

    /**
     * get the global High Score
     *
     * @api
     *
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @param  integer $limit
     * @param  integer $offset
     * @return array 
     */
    public function highScore($limit, $offset);

    /**
     * get the score of one user
     *
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @param  string $username
     * @return integer 
     */
    public function userScore($username);

    /**
     * calculate score
     *
     * @author Martin Kuntizsch 2014
     * @version 1.0
     * @param  integer $starttime
     * @param  integer $endtime
     * @param  integer $complexity
     * @return array 
     */
    public function calculateGameScore($starttime, $endtime, $complexity);
}
