<?php

namespace verbunden\BlendokuBundle\Model;

/**
 * Interface GameInterface
 * 
 * ...
 *
 * @author Benjamin Brandt 2014
 * @version 1.0
 *
 */
Interface GameInterface {

    /**
     * get property $id
     *
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @return int 
     */
    public function getId();

    /**
     * get property $user
     *
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @return User 
     */
    public function getUser();

    /**
     * Set starttime
     *
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @param string unixtime $timestamp
     * @return Game
     */
    public function setStarttime($timestamp);

    /**
     * Get starttime
     *
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @return string unixtime   
     */
    public function getStarttime();

    /**
     * Set endtime
     *
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @param string unixtime $timestamp
     * @return Game
     */
    public function setEndtime($timestamp);

    /**
     * Get endtime
     *
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @return string unixtime 
     */
    public function getEndtime();

    /**
     * Set user
     *
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @param \verbunden\BlendokuBundle\Entity\User $user
     * @return Game
     */
    public function setUser($user);

    /**
     * Set level
     *
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @param \verbunden\BlendokuBundle\Entity\Level $level
     * @return Game
     */
    public function setLevel($level);

    /**
     * Get level
     *
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @return \verbunden\BlendokuBundle\Entity\Level 
     */
    public function getLevel();

    /**
     * Set score
     *
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @param integer $score
     * @return Score
     */
    public function setScore($score);

    /**
     * Generate score
     *
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @param integer $complexity
     * @return Level
     */
    public function setGenScore($starttime, $endtime);

    /**
     * Get score
     *
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @return integer 
     */
    public function getScore();
}
