<?php

namespace verbunden\PromenadeBundle\Handler;

use verbunden\PromenadeBundle\Model\LevelInterface;

/**
 * GameHandlerInterface
 *
 * @package verbunden\PromenadeBundle\Handler
 * @author Benjamin Brandt 2014
 * @version 1.0
 */
interface LevelHandlerInterface {

    /**
     * Start one level given the identifier
     *
     * @api
     *
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @param int $limit  the limit of the result
     * @param int $offset starting from the offset
     * @return array
     */
    public function listLevel($limit = 15, $offset = 0);

    /**
     * Show one level given the identifier
     *
     * @api
     *
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @param integer $level_id
     * @return array
     */
    public function showLevel($level_id);

    /**
     * Solve one level given the parameters
     *
     * @api
     *
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @param integer $level_id
     * @param array $parameters
     * @return bool
     */
    public function solveLevel($level_id, array $parameters);
    
    /**
     * Delete one level given the identifier
     *
     * @api
     *
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @param integer $level_id
     * @param $user_name
     * @param $accesstoken
     * @return bool
     */
    public function deleteLevel($level_id, $user_name, $accesstoken);
}
