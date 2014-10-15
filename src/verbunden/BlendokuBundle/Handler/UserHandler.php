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
class UserHandler implements UserHandlerInterface
{	
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
	public function __construct(ObjectManager $om, $entityClass, FormFactoryInterface $formFactory){
		$this->om = $om;
		$this->entityClass = $entityClass;
		$this->repository = $this->om->getRepository($this->entityClass);
		$this->formFactory = $formFactory;
	}
	
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
	public function verifyUser($password){
		return true;
	}
	
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
	public function showUser($user_id){
		return $this->repository->findOneBy($user_id);
	}
	
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
	public function createUser(array $parameters){
		$user= new User();
		$user->setName($parameters['email']);
		$user->setEmail($parameters['email']);
		$user->setHash($parameters['password']);
		$this->om->persist($user);
		$this->om->flush($user);
		return $user;
	}
	
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
	public function editUser(array $parameters){
		$user= $this->repository->findOneBy($parameters['id']);
		if($user->verifyUser($parameters['oldpass'])){
			$user->setName($parameters['email']);
			$user->setEmail($parameters['email']);
			$user->setHash($parameters['password']);
			$this->om->persist($user);
			$this->om->flush($user);
		}
	}
	
	protected function createNewUser()
	{
		return new $this->entityClass();
	}
}
