<?php

namespace verbunden\BlendokuBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use verbunden\BlendokuBundle\Model\LevelInterface;

/**
 * Leveldatabase
 *
 * @ORM\Entity
 * @ORM\Table(name="level")
 * @author Benjamin Brandt 2014
 * @version 1.0
 */
class Level implements LevelInterface {

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\OneToMany(targetEntity="Game", mappedBy="level", cascade={"persist", "remove"}, orphanRemoval=true)
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @var integer
     */
    private $id;

    /**
     * @ORM\Column(type="array")
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @var array
     */
    private $color;

    /**
     * @ORM\Column(type="array")
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @var array
     */
    private $startgrid;

    /**
     * @ORM\Column(type="array")
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @var array
     */
    private $grid;

    /**
     * @ORM\Column(type="integer")
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @var integer
     */
    private $complexity;

    /**
     * Set id
     *
     * @param integer $id
     * @return Level
     */
    public function setId($id) {
        $this->id = $id;

        return $this;
    }

    /**
     * Get id
     *
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set color
     *
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @param array $color
     * @return Level
     */
    public function setColor($color) {
        $this->color = $color;

        return $this;
    }

    /**
     * Get color
     *
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @return array 
     */
    public function getColor() {
        return $this->color;
    }

    /**
     * Set startgrid
     *
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @param array $startgrid
     * @return Level
     */
    public function setStartgrid($startgrid) {
        $this->startgrid = $startgrid;

        return $this;
    }

    /**
     * Get startgrid
     *
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @return array 
     */
    public function getStartgrid() {
        return $this->startgrid;
    }

    /**
     * Set grid
     *
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @param array $grid
     * @return Level
     */
    public function setGrid($grid) {
        $this->grid = $grid;

        return $this;
    }

    /**
     * Get grid
     *
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @return array 
     */
    public function getGrid() {
        return $this->grid;
    }

    /**
     * Set complexity
     *
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @param integer $complexity
     * @return Level
     */
    public function setComplexity($complexity) {
        $this->complexity = $complexity;

        return $this;
    }

    /**
     * Get complexity
     *
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @return integer 
     */
    public function getComplexity() {
        return $this->complexity;
    }

}
