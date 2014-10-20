<?php

namespace verbunden\BlendokuBundle\Handler;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\FormFactoryInterface;
use verbunden\BlendokuBundle\Model\UserInterface;
// use verbunden\BlendokuBundle\Form\UserType;
use verbunden\BlendokuBundle\Exception\InvalidFormException;

/**
 * User Handler
 *
 * @package verbunden\BlendokuBundle\Handler
 * @author Benjamin Brandt
 */
class UserHandler implements UserHandlerInterface {

    private $om;
    private $entityClass;
    private $repository;
    private $formFactory;

    /**
     * construct
     *
     * @api
     *
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @param ObjectManager $om
     * @param string $entityClass
     * @param FormFactoryInterface $formFactory
     */
    public function __construct(ObjectManager $om, $entityClass) { //, FormFactoryInterface $formFactory) {
        $this->om = $om;
        $this->entityClass = $entityClass;
        $this->repository = $this->om->getRepository($this->entityClass);
        //$this->formFactory = $formFactory;
    }

    /**
     * Verify a user given the parameters
     *
     * @api
     *
     * @author Martin Kunitzsch 2014
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
     * Verify a username by an accesstoken
     *
     * @api
     *
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @param string $accesstoken
     * @param string $username
     * @return object $user
     */
    public function verifyByAccesstoken($accesstoken, $username) {
        $user = $this->repository->findOneByName($username);
        if ($user && $accesstoken == $user->getAccesstoken()) {
            return $user;
        }
        return NULL;
    }

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
    public function generateAccesstoken($user) {
        $user->setAccesstoken();
        $user->setKeyvalidity();
        $this->om->persist($user);
        $this->om->flush($user);
        return $user;
    }

    /**
     * login a user
     *
     * @api
     *
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @param  array $parameters
     * @return array 
     */
    public function loginUser(array $parameters) {
        $username=$this->postedNameLen($parameters['user']['name']);
        $user = $this->showUser($username);
        $password=$this->postedPasswordLen($parameters['user']['password']);
        if ($user) {
            if ($this->verifyUser($user->getHash(), $user->getSalt(), $password)) {
                $user = $this->generateAccesstoken($user);
                return array('name' => $user->getName(),'accesstoken' => $user->getAccesstoken(), 'time' => $user->getKeyvalidity(), 'status' => 'loggedin');
            } else {
                return array('name' => $user->getName(),'accesstoken' => '-', 'time' => ' ', 'status' => 'wrong_password');
            }
        } elseif ($username && $password){
            $user= $this->createUser($username,$password);
            return array('name' => $user->getName(),'accesstoken' => $user->getAccesstoken(), 'time' => $user->getKeyvalidity(), 'status' => 'loggedin');
        } elseif (!$password) {
            return array('name' => '-','accesstoken' => '-', 'time' => '-', 'status' => 'short_password');
        } elseif (!$username) {
            return array('name' => '-','accesstoken' => '-', 'time' => '-', 'status' => 'short_username');
        }
    }

    /**
     * logout a user
     *
     * @api
     *
     * @author Martin Kuntizsch 2014
     * @version 1.0
     * @param array $parameters
     */
    public function logoutUser(array $parameters) {
        $username=$this->postedNameLen($parameters['user']['name']);
        $user = $this->verifyByAccesstoken($parameters['user']['accesstoken'], $username);
        if ($user) {
            $user->invalidateAccesstoken();
            $user->invalidateKeyvalidity();
            $this->om->persist($user);
            $this->om->flush($user);
            return array('name' => $user->getName(),'accesstoken' => ' ', 'time' => ' ', 'status' => 'loggedout');
        }
        return array('name' => $username,'accesstoken' => ' ','error' => 'user_or_accesstoken_not_exists','status' => 'failed');
    }

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
    public function showUser($user_name) {
        return $this->repository->findOneByName($user_name);
    }

    /**
     * Create one user given the parameters
     *
     * @api
     *
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @para sting $username
     * @parm string $password
     * @return array user object
     */
    public function createUser($username,$password) {
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
     * Edit one user password given the parameters
     *
     * @api
     *
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @param array $parameters
     * @return array
     */
    public function editPassword(array $parameters) {
        $user = $this->repository->findOneBy($parameters['id']);
        if ($user) {
            $user->setName($parameters['user']['name']);
            $user->setHash($parameters['user']['password']);
            $this->om->persist($user);
            $this->om->flush($user);
            return array(true);
        }
    }

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
        if (strlen($string) < 5){
            $string = NULL;
        }else{
            $string = strtolower($string);
        }
        if ($string == 'admin' || $string == 'guest'){
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
        if (strlen($string) < 5){
            $string = NULL;
        }
        return $string;
    }
}
