<?php

namespace verbunden\BlendokuBundle\Model;

/**
 * Interface LevelInterface
 * 
 * ...
 *
 * @author Benjamin Brandt 2014
 * @version 1.0
 *
 */
Interface LevelInterface {

    /**
     * Set id
     *
     * @param integer $id
     * @return Level
     */
    public function setId($id);

    /**
     * Get id
     *
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @return integer 
     */
    public function getId();

    /**
     * Set color
     *
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @param array $color
     * @return Level
     */
    public function setColor($color);

    /**
     * Get color
     *
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @return array 
     */
    public function getColor();

    /**
     * Set startgrid
     *
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @param array $startgrid
     * @return Level
     */
    public function setStartgrid($startgrid);

    /**
     * Get startgrid
     *
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @return array 
     */
    public function getStartgrid();

    /**
     * Set grid
     *
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @param array $grid
     * @return Level
     */
    public function setGrid($grid);

    /**
     * Get grid
     *
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @return array 
     */
    public function getGrid();

    /**
     * Set complexity
     *
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @param integer $complexity
     * @return Level
     */
    public function setComplexity($complexity);

    /**
     * Get complexity
     *
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @return integer 
     */
    public function getComplexity();
}
