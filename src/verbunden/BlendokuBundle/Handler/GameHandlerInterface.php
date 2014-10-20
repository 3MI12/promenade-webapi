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
     * Start a game given the level identifier
     *
     * @api
     *
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @param integer $level_id
     * @return array
     */
    public function startGame(array $parameters);

    /**
     * Solve a game given the level identifier
     *
     * @api
     *
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @param integer $level_id
     * @param array $parameters
     * @return array
     */
    public function solveGame(array $parameters);
    
    /**
     * calculate score
     *
     * @api
     *
     * @author Martin Kuntizsch 2014
     * @version 1.0
     * @param  integer $starttime
     * @param  integer $endtime
     * @param  integer $complexity
     * @return integer 
     */
    public function calculateGameScore($starttime, $endtime, $complexity);
    
    /**
     * calculate user score
     *
     * @api
     *
     * @author Martin Kuntizsch 2014
     * @version 1.0
     * @param  string $username
     * @return array 
     */
    public function calculateUserScore($username);
    
    /**
     * calculate highscore
     *
     * @api
     *
     * @author Martin Kuntizsch 2014
     * @version 1.0
     * @return array 
     */
    public function calculateHighScore();
}
