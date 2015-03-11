<?php

namespace verbunden\PromenadeBundle\Handler;

use Doctrine\Common\Persistence\ObjectManager;
use verbunden\PromenadeBundle\Model\UserInterface;

/**
 * User Handler
 *
 * @package verbunden\PromenadeBundle\Handler
 * @author Benjamin Brandt
 */
class UserHandler implements UserHandlerInterface {

    private $om;
    private $entityClass;
    private $repository;

    /**
     * construct
     *
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @param ObjectManager $om
     * @param string $entityClass
     */
    public function __construct(ObjectManager $om, $entityClass) {
        $this->om = $om;
        $this->entityClass = $entityClass;
        $this->repository = $this->om->getRepository($this->entityClass);
    }

    /**
     * login a user
     *
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @param  array $parameters
     * @return array 
     */
    public function loginUser(array $parameters) {
        $username = $this->postedNameLen($parameters['user']['name']);
        $user = $this->showUser($username);
        $password = $this->postedPasswordLen($parameters['user']['password']);
        if ($user) {
            if (crypt($password, $user->getSalt()) == $user->getHash()) {
                $user = $this->generateAccesstoken($user);
                return array('name' => $user->getName(), 'accesstoken' => $user->getAccesstoken(), 'time' => $user->getKeyvalidity(), 'status' => 'loggedin');
            } else {
                return array('name' => $user->getName(), 'accesstoken' => '-', 'time' => ' ', 'status' => 'wrong_password');
            }
        } elseif ($username && $password) {
            $user = $this->createUser($username, $password);
            return array('name' => $user->getName(), 'accesstoken' => $user->getAccesstoken(), 'time' => $user->getKeyvalidity(), 'status' => 'loggedin');
        } elseif (!$password) {
            return array('name' => '-', 'accesstoken' => '-', 'time' => '-', 'status' => 'short_password');
        } elseif (!$username) {
            return array('name' => '-', 'accesstoken' => '-', 'time' => '-', 'status' => 'short_username');
        }
    }

    /**
     * logout a user
     *
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @param array $parameters
     */
    public function logoutUser(array $parameters) {
        $username = $this->postedNameLen($parameters['user']['name']);
        $user = $this->showUser($username);
        if ($user && $user->getAccesstoken()==$parameters['user']['accesstoken']) {
            $user->invalidateAccesstoken();
            $user->invalidateKeyvalidity();
            $this->om->persist($user);
            $this->om->flush($user);
            return array('name' => $user->getName(), 'accesstoken' => ' ', 'time' => ' ', 'status' => 'loggedout');
        }
        return array('name' => $username, 'accesstoken' => ' ', 'error' => 'user_or_accesstoken_not_exists', 'status' => 'failed');
    }

    /**
     * Show one user given the identifier
     *
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @param string $user_name
     * @return array
     */
    public function showUser($user_name) {
        return $this->repository->findOneByName($user_name);
    }

    /**
     * Create one user given the parameters
     * 
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @para sting $username
     * @parm string $password
     * @return array user object
     */
    public function createUser($username, $password) {
        $user = $this->createNewUser();
        $user->setName($username);
        $user->setSalt();
        $user->setAccesstoken();
        $user->setKeyvalidity();
        $user->setHash(crypt($password, $user->getSalt()));
        $this->om->persist($user);
        $this->om->flush($user);
        return $user;
    }

    /**
     * Edit user password
     *
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @param array $parameters
     * @return array
     */
    public function editPassword(array $parameters) {
        $username = $this->postedNameLen($parameters['user']['name']);
        $user = $this->showUser($username);
        $password = $this->postedPasswordLen($parameters['user']['password']);
        if ($user && $password && $user->getAccesstoken()==$parameters['user']['accesstoken']) {
            $user->setHash(crypt($password, $user->getSalt()));
            $this->om->persist($user);
            $this->om->flush($user);
            return array('name' => $user->getName(), 'status' => 'password_changed');
        } else {
            return array('name' => $username, 'status' => 'user_or_accesstoken_not_exists');   
        }
    }

    /**
     * create new userobejct
     *
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @return object user
     */
    protected function createNewUser() {
        return new $this->entityClass();
    }

    /**
     * Validate posted Username
     *
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @param  string $string
     * @return string $string
     */
    protected function postedNameLen($string) {
        if (strlen($string) < 5) {
            $string = NULL;
        } else {
            $string = strtolower($string);
        }
        if ($string == 'admin' || $string == 'guest') {
            $string = NULL;
        }
        return $string;
    }

    /**
     * Validate posted Password
     *
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @param  string $string
     * @return string $string
     */
    protected function postedPasswordLen($string) {
        if (strlen($string) < 5) {
            $string = NULL;
        }
        return $string;
    }

    /**
     * Verify a user given the parameters
     *
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @parm   string  passwordhash  $hash
     * @param  string  usersalt      $salt
     * @param  string  usersalt      $name
     * @return bool
     */
    public function verifyUser($hash, $salt, $password) {
        $hashPost = crypt($password, $salt);
        if ($hash == $hashPost) {
            return true;
        }
        return false;
    }
    
    /**
     * Verify a user given name and accesstoken
     *
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @parm   string  passwordhash  $name
     * @param  string  usersalt      $accesstoken
     * @return bool
     */
    public function verifyAccesstoken($name, $accesstoken) {
        $user = UserHandler::showUser($name);
        if ($user && $accesstoken && $accesstoken == $user->$getAccesstoken){
            return true;
        }
        return false;
    }

    /**
     * create accesstoken
     *
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @param  array $user
     * @return string
     */
    protected function generateAccesstoken($user) {
        $user->setAccesstoken();
        $user->setKeyvalidity();
        $this->om->persist($user);
        $this->om->flush($user);
        return $user;
    }

}
