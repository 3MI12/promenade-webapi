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
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\OneToMany(targetEntity="Game", mappedBy="user", cascade={"persist", "remove"}, orphanRemoval=true)
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @var integer
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=200, nullable=false, unique=true)
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @var string
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=200, nullable=false, unique=true)
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @var string
     */
    private $email;

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
    private $accesskey;
    
    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @var string
     */
    private $keyvalidity;
    
    /**
     * construct
     *
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @param string $salt
     * @return int 
     */
    public function __construct() {
        $this->salt = "$6$rounds=10000$".md5(uniqid(null,true));
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
     * Set email
     *
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @param string $email
     * @return User
     */
    public function setEmail($email) {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @return string 
     */
    public function getEmail() {
        return $this->email;
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
     * @param string $salt
     * @return User
     */
    public function setSalt($salt) {
        $this->hash = $salt;

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
     * Set accesskey
     *
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @param string $accesskey
     * @return User
     */
    public function setAccesskey($accesskey) {
        $this->hash = $accesskey;

        return $this;
    }

    /**
     * Get accesskey
     *
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @return string 
     */
    public function getAccesskey() {
        return $this->salt;
    }
    
    /**
     * Set keyvalidity
     *
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @param \DateTime $keyvalidity
     * @return User
     */
    public function setKeyvalidity($time = "now") {
        $this->keyvalidity = new \DateTime($time);

        return $this;
    }

    /**
     * Get keyvalidity
     *
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @return string 
     */
    public function getKeyvalidity() {
        return $this->salt;
    }
}
