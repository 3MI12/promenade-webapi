<?php
// src/verbunden/BlendokuBundle/Entity/Level.php

namespace verbunden\BlendokuBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="color")
 */
class Color
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\OneToMany(targetEntity="Game", mappedBy="level", cascade={"ALL"})
     * @var integer
     */
    private $id;

    /**
     * @ORM\Column(type="array")
     * @var array
     */
    private $color;

    /**
     * @ORM\Column(type="array")
     * @var array
     */
    private $startgrid;

	/**
     * @ORM\Column(type="array")
     * @var array
     */
    private $grid;

    /**
     * @ORM\Column(type="integer")
     * @var integer
     */
    private $complexity;

	/**
 	* Get a certain level by id.
 	* 
 	* @author Benjamin Brandt 2014
 	* @version 1.0
 	* 
 	* @param entityManager $entityManager
 	* @param id $id
 	* @return user object
 	*/
	public static function getUserById($id) {
		return $entityManager->getRepository('verbundenBlendokuBundle:Level')->findOneById($id);
	}
	
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set color
     *
     * @param array $color
     * @return Level
     */
    public function setColor($color)
    {
        $this->color = $color;

        return $this;
    }

    /**
     * Get color
     *
     * @return array 
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * Set startgrid
     *
     * @param array $startgrid
     * @return Level
     */
    public function setStartgrid($startgrid)
    {
        $this->startgrid = $startgrid;

        return $this;
    }

    /**
     * Get startgrid
     *
     * @return array 
     */
    public function getStartgrid()
    {
        return $this->startgrid;
    }

    /**
     * Set grid
     *
     * @param array $grid
     * @return Level
     */
    public function setGrid($grid)
    {
        $this->grid = $grid;

        return $this;
    }

    /**
     * Get grid
     *
     * @return array 
     */
    public function getGrid()
    {
        return $this->grid;
    }

    /**
     * Set complexity
     *
     * @param integer $complexity
     * @return Level
     */
    public function setComplexity($complexity)
    {
        $this->complexity = $complexity;

        return $this;
    }

    /**
     * Get complexity
     *
     * @return integer 
     */
    public function getComplexity()
    {
        return $this->complexity;
    }
}
