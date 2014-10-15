<?php
namespace verbunden\BlendokuBundle\Handler;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\FormFactoryInterface;
use verbunden\BlendokuBundle\Model\GameInterface;
use verbunden\BlendokuBundle\Model\LevelInterface;
use verbunden\BlendokuBundle\Form\GameType;
use verbunden\BlendokuBundle\Exception\InvalidFormException;

/**
 * GameHandler
 *
 * @package verbunden\BlendokuBundle\Handler
 * @author Benjamin Brandt
 */
class GameHandler implements GameHandlerInterface
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
	public function __construct(ObjectManager $om, $entityClass){ //, FormFactoryInterface $formFactory){
		$this->om = $om;
		$this->entityClass = $entityClass;
		$this->repository = $this->om->getRepository($this->entityClass);
		//$this->formFactory = $formFactory;
	}
	
	/**
     * Start one level given the identifier
     *
	 * @api
     *
	 * @author Benjamin Brandt 2014
	 * @version 1.0
	 * @param array $parameters
     * @return array
     */
	public function startGame(array $parameters){
		$em = $this->getDoctrine()->getManager();
		//$level = $this->getDoctrine()->getRepository('verbundenBlendokuBundle:Level')->findOneById($level_id);
		$game = $this->getDoctrine()->getRepository('verbundenBlendokuBundle:Game')->findOneBy(array('user' => '1', 'level' => $level_id));
		if(!$game){
			$game = new Game('1',$level_id);
		}
		$game->getStarttime("now");
		return $game;
		$em->persist($game);
		$em->flush();
	}

	/**
     * Solve one level given the parameters
     *
	 * @api
     *
	 * @author Benjamin Brandt 2014
	 * @version 1.0
	 * @param array $parameters
     * @return array
     */
	public function solveGame(array $parameters){
		return true;
	}
	
	protected function createNewGame()
	{
		return new $this->entityClass();
	}
}
