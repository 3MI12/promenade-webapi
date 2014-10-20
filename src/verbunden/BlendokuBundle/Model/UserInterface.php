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
     * @return User
     */
    public function setSalt();

    /**
     * Get salt
     *
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @return string 
     */
    public function getSalt();

    /**
     * Set accesstoken
     *
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @return User
     */
    public function setAccesstoken();

    /**
     * Invalidate accesstoken
     *
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @return User
     */
    public function invalidateAccesstoken();
    
    /**
     * Get accesstoken
     *
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @return string 
     */
    public function getAccesstoken();

    /**
     * Set keyvalidity
     *
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @return User
     */
    public function setKeyvalidity();

    /**
     * invalidate keyvalidity
     *
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @return User
     */
    public function invalidateKeyvalidity();
            
    /**
     * Get keyvalidity
     *
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @return integer unix timestamp $keyvalidity
     */
    public function getKeyvalidity();
}
