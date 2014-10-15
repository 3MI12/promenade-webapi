<?php
namespace verbunden\BlendokuBundle\Handler;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\FormFactoryInterface;
use verbunden\BlendokuBundle\Model\LevelInterface;
use verbunden\BlendokuBundle\Form\LevelType;
use verbunden\BlendokuBundle\Exception\InvalidFormException;

/**
 * LevelHandler
 *
 * @package verbunden\BlendokuBundle\Handler
 * @author Benjamin Brandt
 */
class LevelHandler implements LevelHandlerInterface
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
     * List all level
     *
	 * @api
     *
	 * @author Benjamin Brandt 2014
	 * @version 1.0
	 * @param int $limit  the limit of the result
	 * @param int $offset starting from the offset
     * @return array
     */
	public function listLevel($limit = 5, $offset = 0){
		return $this->repository->findBy(array(), null, $limit, $offset);
	}
	
	/**
     * Get one level given the identifier
     *
	 * @api
     *
	 * @author Benjamin Brandt 2014
	 * @version 1.0
	 * @param integer $id
     * @return array
     */
	public function showLevel($level_id){
		return $this->repository->findOneById($level_id);
	}

	/**
     * Verify one level given the parameters
     *
	 * @api
     *
	 * @author Benjamin Brandt 2014
	 * @version 1.0
	 * @param integer $id
     * @return bool
     */
	public function solveLevel($level_id){
		$parameters = $this->repository->findOneById($level_id);
		$grid = $this->repository->findOneById($level_id);
		if($parameters && $grid && $parameters->getGrid() == $grid->getGrid()){
			return array('level_id'=>$level_id, 'solved'=>true);
		}else{
			return array('level_id'=>$level_id, 'solved'=>false);
		}	
	}
	
	/**
     * Create one level
     *
	 * @api
     *
	 * @author Benjamin Brandt 2014
	 * @version 1.0
	 * @param array $parameters
     * @return array
     */
	public function createLevel(array $parameters){		
		$game['color']=array();	
		
		for ($i = 0; $i < 100; $i++) {
	    	if (isset($parameters['free'][$i])){
				$game['grid'][$i]=array('color' => $parameters['free'][$i],'edit' => false);
				$game['startgrid'][$i]=array('color' => '#6b6b6b','edit' => true);
				array_push($game['color'], $parameters['free'][$i]);
			}elseif(isset($parameters['set'][$i])){
				$game['grid'][$i]=array('color' => $parameters['set'][$i],'edit' => false);
				$game['startgrid'][$i]=array('color' => $parameters['set'][$i],'edit' => false);
			}
		}
		
		$level = LevelHandler::createNewLevel();
		$level->setId($parameters['level_id']);
		$level->setColor($game['color']);	
		$level->setComplexity($parameters['complexity']);
		$level->setGrid($game['grid']);
		$level->setStartgrid($game['startgrid']);

		$this->om->persist($level);
		$this->om->flush($level);
		
	    return $level;
	}
	
	/**
     * Edit one level given the parameters
     *
	 * @api
     *
	 * @author Benjamin Brandt 2014
	 * @version 1.0
	 * @param array $parameters
     * @return GameInterface
     */
	public function editLevel(array $parameters){
		return NULL;
	}
	
	/**
     * Delete one level given the identifier
     *
	 * @api
     *
	 * @author Benjamin Brandt 2014
	 * @version 1.0
	 * @param integer $id
     * @return bool
     */
	public function deleteLevel($level_id){
		$level = $this->getDoctrine()->getRepository('verbundenBlendokuBundle:Level')->findOneById($level_id);
		$em->remove($level);
		$em->flush();
	    return $level;
	}
	
	
	/**
     * Get one level.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @Annotations\QueryParam(name="offset", requirements="\d+", nullable=true, description="Offset from which to start listing level.")
     *
     * @Annotations\View(
     *  templateVar="level"
     * )
     *
     * @param Request               $request      the request object
     * @param ParamFetcherInterface $paramFetcher param fetcher service
     *
     * @return array
     */
	public function getNumberStarttestAction($level_id)
	{
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
     * Post/Solve level.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @Annotations\QueryParam(name="offset", requirements="\d+", nullable=true, description="Offset from which to start listing level.")
     *
     * @Annotations\View(
     *  templateVar="level"
     * )
     *
     * @param Request               $request      the request object
     * @param ParamFetcherInterface $paramFetcher param fetcher service
     *
     * @return array
     */
	public function postNumberSolveAction($level_id)
	{
	    return $this->getDoctrine()->getRepository('verbundenBlendokuBundle:Level')->findOneById($id);
	}
	
	/**
     * Create a level.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     *
     * @Annotations\View(
     *  templateVar="level"
     * )
     *
     * @param Request               $request      the request object
     * @param ParamFetcherInterface $paramFetcher param fetcher service
     *
     * @return array
     */
	public function getNumberCreateAction($level_id)
	{
	    
	}
	
	/**
     * Edit a level.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     *
     * @Annotations\View(
     *  templateVar="level"
     * )
     *
     * @param Request               $request      the request object
     * @param ParamFetcherInterface $paramFetcher param fetcher service
     *
     * @return array
     */
	public function postNumberEditAction($level_id)
	{
	    $color['23'] = '#a2a2a2';
		$free['24'] = '#';
		$free['25'] = '#';
		$free['26'] = '#';
		$free['27'] = '#';
		$free['28'] = '#';
		$color['29'] = '#a4a4a4';
		$game['color']=array('#a3a3a3','#a0a0a0','#a1a1a1');

		for ($i = 0; $i < 100; $i++) {
	    	if (isset($free[$i])){
				$game['grid'][$i]=array('color' => '#6b6b6b','edit' => true);
			}elseif(isset($color[$i])){
				$game['grid'][$i]=array('color' => $color[$i],'edit' => false);
			}
		}
				
		$level = $this->getDoctrine()->getRepository('verbundenBlendokuBundle:Level')->findOneById($level_id);
		$level->setColor($game['color']);	
		$level->setComplexity('3');
		$level->setGrid($game['grid']);
		$level->setStartgrid($game['grid']);		
		$em = $this->getDoctrine()->getManager();
		$em->persist($level);
		$em->flush();
	    return $level;
	}

	
	/**
     * List all level.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @Annotations\QueryParam(name="offset", requirements="\d+", nullable=true, description="Offset from which to start listing users.")
     * @Annotations\QueryParam(name="limit", requirements="\d+", default="5", description="How many users to return.")
     *
     * @Annotations\View(
     *  templateVar="level"
     * )
     *
     * @param Request               $request      the request object
     * @param ParamFetcherInterface $paramFetcher param fetcher service
     *
     * @return array
     */
	public function getListAction()
	{
	    return $this->getDoctrine()->getRepository('verbundenBlendokuBundle:Level')->findAll();
	}
	
	protected function createNewLevel()
	{
		return new $this->entityClass();
	}
}
