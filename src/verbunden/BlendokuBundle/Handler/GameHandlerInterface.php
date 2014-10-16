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
     * @param array $parameters
     * @return array
     */
    public function solveGame(array $parameters);
}
