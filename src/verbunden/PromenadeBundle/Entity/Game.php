<?php

namespace verbunden\PromenadeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use verbunden\PromenadeBundle\Model\GameInterface;
use verbunden\PromenadeBundle\Model\LevelInterface;
use verbunden\PromenadeBundle\Model\UserInterface;

/**
 * Gamedatabase
 *
 * @ORM\Entity
 * @ORM\Table(name="`game`")
 * @author Benjamin Brandt
 * @version 1.0
 */
class Game implements GameInterface {

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="User", inversedBy="name", cascade={"persist"})
     * @ORM\JoinColumn(name="user_name", referencedColumnName="name", nullable=false)
     * @author Benjamin Brandt
     * @version 1.0
     * @var User who played the game
     * */
    private $user;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Level", inversedBy="id", cascade={"persist"})
     * @ORM\JoinColumn(name="level_id", referencedColumnName="id", nullable=false)
     * @author Benjamin Brandt
     * @version 1.0
     * @var Level that the gamer has played
     * */
    private $level;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @author Benjamin Brandt
     * @version 1.0
     * @var start unix timestamp of the game
     */
    private $starttime;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @author Benjamin Brandt
     * @version 1.0
     * @var end unix timestamp of the game
     */
    private $endtime;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @author Benjamin Brandt
     * @version 1.0
     * @var integer
     */
    private $score;

    /**
     * get property $user
     *
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @return User 
     */
    public function getUser() {
        return $this->user;
    }

    /**
     * Set user
     *
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @param \verbunden\PromenadeBundle\Entity\User $user
     * @return Game
     */
    public function setUser($user) {
        $this->user = $user;

        return $this;
    }

    /**
     * Set level
     *
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @param \verbunden\PromenadeBundle\Entity\Level $level
     * @return Game
     */
    public function setLevel($level) {
        $this->level = $level;

        return $this;
    }

    /**
     * Get level
     *
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @return \verbunden\PromenadeBundle\Entity\Level 
     */
    public function getLevel() {
        return $this->level;
    }

    /**
     * Set starttime
     *
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @param integer unixtime $timestamp
     * @return Game
     */
    public function setStarttime($timestamp) {
        $this->starttime = $timestamp;
        return $this;
    }

    /**
     * Get starttime
     *
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @return integer unixtime   
     */
    public function getStarttime() {
        return $this->starttime;
    }

    /**
     * Set endtime
     *
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @param integer unixtime $timestamp
     * @return Game
     */
    public function setEndtime($timestamp) {
        $this->endtime = $timestamp;

        return $this;
    }

    /**
     * Get endtime
     *
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @return integer unixtime 
     */
    public function getEndtime() {
        return $this->endtime;
    }

    /**
     * Set score
     *
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @param integer $score
     * @return Score
     */
    public function setScore($score) {
        $this->score = $score;

        return $this;
    }

    /**
     * Get score
     *
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @return integer 
     */
    public function getScore() {
        return $this->score;
    }

}
