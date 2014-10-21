<?php

namespace verbunden\BlendokuBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use verbunden\BlendokuBundle\Model\UserInterface;

/**
 * Userdatabase
 *
 * @ORM\Entity
 * @ORM\Table(name="user")
 * @author Benjamin Brandt 2014
 * @version 1.0
 */
class User implements UserInterface {

    /**
     * @ORM\Column(type="string", length=200)
     * @ORM\Id
     * @ORM\OneToMany(targetEntity="Game", mappedBy="user", cascade={"persist", "remove"}, orphanRemoval=true)
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @var string
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=300, nullable=false)
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @var string
     */
    private $hash;

    /**
     * @ORM\Column(type="string", length=300, nullable=false)
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @var string
     */
    private $salt;

    /**
     * @ORM\Column(type="string", length=300, nullable=true)
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @var string
     */
    private $accesstoken;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @var integer
     */
    private $keyvalidity;

    /**
     * Set name
     *
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @param string $name
     * @return User
     */
    public function setName($name) {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @return string 
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Set hash
     *
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @param string $hash
     * @return User
     */
    public function setHash($hash) {
        $this->hash = $hash;

        return $this;
    }

    /**
     * Get hash
     *
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @return string 
     */
    public function getHash() {
        return $this->hash;
    }

    /**
     * Set salt
     *
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @return User
     */
    public function setSalt() {
        $this->salt = '$6$rounds=10000$' . md5(uniqid(null, true));

        return $this;
    }

    /**
     * Get salt
     *
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @return string 
     */
    public function getSalt() {
        return $this->salt;
    }

    /**
     * Set accesstoken
     *
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @return User
     */
    public function setAccesstoken() {
        $this->accesstoken = md5(uniqid(null, true));

        return $this;
    }

    /**
     * Invalidate accesstoken
     *
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @return User
     */
    public function invalidateAccesstoken() {
        $this->accesstoken = NULL;

        return $this;
    }

    /**
     * Get accesstoken
     *
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @return string 
     */
    public function getAccesstoken() {
        return $this->accesstoken;
    }

    /**
     * Set keyvalidity
     *
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @return User
     */
    public function setKeyvalidity() {
        $this->keyvalidity = time() + 60 * 60;

        return $this;
    }

    /**
     * invalidate keyvalidity
     *
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @return User
     */
    public function invalidateKeyvalidity() {
        $this->keyvalidity = 0;

        return $this;
    }

    /**
     * Get keyvalidity
     *
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @return integer unix timestamp $keyvalidity
     */
    public function getKeyvalidity() {
        return $this->keyvalidity;
    }

}
