<?php

namespace verbunden\BlendokuBundle\Handler;

use verbunden\BlendokuBundle\Model\LevelInterface;

/**
 * GameHandlerInterface
 *
 * @package verbunden\BlendokuBundle\Handler
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
    public function listLevel($limit = 5, $offset = 0);

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
     * @return bool
     */
    public function solveLevel($level_id);

    /**
     * Edit/Create one level given the parameters
     *
     * @api
     *
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @param array $parameters
     * @return array
     */
    public function editLevel(array $parameters);

    /**
     * Delete one level given the identifier
     *
     * @api
     *
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @param integer $id
     * @return bool
     */
    public function deleteLevel($level_id);
}
