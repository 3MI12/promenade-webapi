<?php

namespace verbunden\BlendokuBundle\Model;

/**
 * Interface UserInterface
 * 
 * ...
 *
 * @author Benjamin Brandt
 * @version 1.0
 *
 */
Interface UserInterface {

    /**
     * construct
     *
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @param string $salt
     * @return int 
     */
    public function __construct();

    /**
     * Get id
     *
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @return integer 
     */
    public function getId();

    /**
     * Set name
     *
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @param string $name
     * @return User
     */
    public function setName($name);

    /**
     * Get name
     *
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @return string 
     */
    public function getName();

    /**
     * Set email
     *
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @param string $email
     * @return User
     */
    public function setEmail($email);

    /**
     * Get email
     *
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @return string 
     */
    public function getEmail();

    /**
     * Set hash
     *
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @param string $hash
     * @return User
     */
    public function setHash($hash);

    /**
     * Get hash
     *
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @return string 
     */
    public function getHash();

    /**
     * Set salt
     *
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @param string $hash
     * @return User
     */
    public function setSalt($salt);

    /**
     * Get salt
     *
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @return string 
     */
    public function getSalt();
}
