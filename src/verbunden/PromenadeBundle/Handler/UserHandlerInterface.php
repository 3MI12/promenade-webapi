<?php

namespace verbunden\PromenadeBundle\Handler;

use verbunden\PromenadeBundle\Model\UserInterface;

/**
 * UserHandlerInterface
 *
 * @package verbunden\PromenadeBundle\Handler
 * @author Benjamin Brandt 2014
 * @version 1.0
 */
interface UserHandlerInterface {
    /**
     * login a user
     *
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @param  array $parameters
     * @return array 
     */
    public function loginUser(array $parameters);

    /**
     * logout a user
     *
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @param array $parameters
     */
    public function logoutUser(array $parameters);

    /**
     * Show one user given the identifier
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
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @para sting $username
     * @parm string $password
     * @return array user object
     */
    public function createUser($username, $password);

    /**
     * Edit user password
     *
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @param array $parameters
     * @return array
     */
    public function editPassword(array $parameters);
    
}
