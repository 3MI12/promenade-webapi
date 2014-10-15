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
interface UserHandlerInterface
{	
	/**
     * Verify a user given the parameters
     *
	 * @api
     *
	 * @author Benjamin Brandt 2014
	 * @version 1.0
	 * @param integer array $parameters
     * @return array
     */
	public function verifyUser(array $parameters);
	
	/**
     * Show one user given the identifier
     *
	 * @api
     *
	 * @author Benjamin Brandt 2014
	 * @version 1.0
	 * @param integer $user_id
     * @return array
     */
	public function showUser($user_id);
	
	/**
     * Create one user given the parameters
     *
	 * @api
     *
	 * @author Benjamin Brandt 2014
	 * @version 1.0
	 * @param array $parameters
     * @return array
     */
	public function createUser(array $parameters);
	
	/**
     * Edit one user given the parameters
     *
	 * @api
     *
	 * @author Benjamin Brandt 2014
	 * @version 1.0
	 * @param array $parameters
     * @return array
     */
	public function editUser(array $parameters);
}
