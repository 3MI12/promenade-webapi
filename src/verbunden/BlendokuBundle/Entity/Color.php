<?php
// src/verbunden/BlendokuBundle/Entity/Color.php

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
     * @var integer
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     * @var array
     */
    private $hex;
	
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
    public function setHex($hex)
    {
        $this->hex = $hex;

        return $this;
    }

    /**
     * Get color
     *
     * @return string 
     */
    public function getHex()
    {
        return $this->hex;
    }
}
