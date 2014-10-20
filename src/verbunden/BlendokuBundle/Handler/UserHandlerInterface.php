<?php

namespace verbunden\BlendokuBundle\Handler;

use verbunden\BlendokuBundle\Model\UserInterface;

/**
 * UserHandlerInterface
 *
 * @package verbunden\BlendokuBundle\Handler
 * @author Benjamin Brandt 2014
 * @version 1.0
 */
interface UserHandlerInterface {

    /**
     * Verify a user given the parameters
     *
     * @api
     *
     * @author Martin Kunitzsch 2014
     * @version 1.0
     * @parm   string  passwordhash  $hash
     * @param  string  usersalt      $salt
     * @param  string  password      $password
     * @return bool
     */
    public function verifyUser($hash, $salt, $password);

    /**
     * Verify a username by an accesstoken
     *
     * @api
     *
     * @author Martin Kunitzsch 2014
     * @version 1.0
     * @param string $accesstoken
     * @param string $username
     * @return string
     */
    public function verifyByAccesstoken($accesstoken, $username);

    /**
     * create accesstoken
     *
     * @api
     *
     * @author Martin Kunitzsch 2014
     * @version 1.0
     * @param  array $user
     * @return string
     */
    public function generateAccesstoken($user);

    /**
     * login a user
     *
     * @api
     *
     * @author Martin Kuntizsch 2014
     * @version 1.0
     * @param  array $parameters
     * @return array 
     */
    public function loginUser(array $parameters);

    /**
     * logout a user
     *
     * @api
     *
     * @author Martin Kuntizsch 2014
     * @version 1.0
     * @param array $parameters
     */
    public function logoutUser(array $parameters);

    /**
     * Show one user given the identifier
     *
     * @api
     *
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @param string $user_name
     * @return array
     */
    public function showUser($user_name);

    /**
     * Create one user given the parameters
     *
     * @api
     *
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @param string $username
     * @param string $password
     * @return array
     */
    public function createUser($username,$password);

    /**
     * Edit one user password given the parameters
     *
     * @api
     *
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @param array $parameters
     * @return array
     */
    public function editPassword(array $parameters);
}
