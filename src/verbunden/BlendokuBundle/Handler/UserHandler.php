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
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @param  array $parameters
     * @param  $password userpassword
     * @return array
     */
    public function verifyUser(array $parameters, $password) {
        return true;
    }

    /**
     * login a user
     *
     * @api
     *
     * @author Martin Kuntizsch 2014
     * @version 1.0
     * @param  array $parameters
     * @return string 
     */
    public function loginUser(array $parameters) {
        $postData = $this->processForm($page, $parameters, 'POST');
        $user = showUser($postData['username']);
        if ($user && verifyUser($user, $postData['password'])) {
            return generateAccessKey($user);
        }
    }

    /**
     * Processes the form.
     *
     * @author Martin Kuntizsch 2014
     * @param PageInterface $page
     * @param array         $parameters
     * @param String        $method
     *
     * @return PageInterface
     *
     * @throws \Acme\BlogBundle\Exception\InvalidFormException
     */
    private function processForm(PageInterface $page, array $parameters, $method = "PUT") {
        $form = $formFactory->createBuilder()
                            ->add('name', 'text')
                            ->add('hash', 'text')
                            ->getForm();
        
        throw new InvalidFormException('Invalid submitted data', $form);
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
     * @param array $parameters
     * @return array
     */
    public function createUser(array $parameters) {
        $user = new User();
        $user->setName($parameters['email']);
        $user->setEmail($parameters['email']);
        $user->setHash(crypt($parameters['password'], $user->getSalt));
        $user->setAccesskey(md5(uniqid(null, true)));
        $user->setKeyvalidity($time = "now");
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
        if ($user->verifyUser($parameters)) {
            $user->setName($parameters['email']);
            $user->setEmail($parameters['email']);
            $user->setHash($parameters['password']);
            $this->om->persist($user);
            $this->om->flush($user);
            return array(true);
        }
    }

    protected function createNewUser() {
        return new $this->entityClass();
    }

}
