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
Interface GameInterface 
{
	/**
	* construct
	*
	* @author Benjamin Brandt 2014
	* @version 1.0
	* @param string $user_id
	* @param string $level_id
	* @return int 
	*/
 	public function __construct($user_id, $level_id);
	
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
     * @param string $starttime
     * @return Game
     */
    public function setStarttime($time="now");

    /**
     * Get starttime
     *
	 * @author Benjamin Brandt 2014
	 * @version 1.0
     * @return \DateTime 
     */
    public function getStarttime();

    /**
     * Set endtime
     *
	 * @author Benjamin Brandt 2014
	 * @version 1.0
     * @param \DateTime $endtime
     * @return Game
     */
    public function setEndtime($time="now");

    /**
     * Get endtime
     *
	 * @author Benjamin Brandt 2014
	 * @version 1.0
     * @return \DateTime 
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
