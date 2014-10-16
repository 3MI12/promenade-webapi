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
class GameHandler implements GameHandlerInterface {

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
    public function __construct(ObjectManager $om, $entityClass) { //, FormFactoryInterface $formFactory){
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
    public function startGame(array $parameters) {
        $game = $this->repository->findOneBy($parameters);
        if (!$game) {
            $game = gameHandler::createNewGame();
            $user = $this->om->getRepository('verbundenBlendokuBundle:User')->findOneById($parameters['user']);
            $level = $this->om->getRepository('verbundenBlendokuBundle:Level')->findOneById($parameters['level']);
            $game->setUser($user);
            $game->setLevel($level);
        }
        $game->setStarttime("now");
        $this->om->persist($game);
        $this->om->flush($game);
        return $game;
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
    public function solveGame(array $parameters) {
        $game = $this->repository->findOneBy(array('user'=>$parameters['user_id'],'level'=>$parameters['level_id']));
        if ($game && $game->getStarttime()==$parameters['starttime']) {
            if($game->getGrid() < $parameters['grid']){
               if($game->getScore() < $parameters['score']){
                $game->setScore() = $parameters['score'];
                $this->om->persist($game);
                $this->om->flush($game);
                }
                return array('level_id' => $parameters['level_id'],'user_id' => $parameters['user_id'],'error'=>'','score'=>$parameters['score'],'solved'=>'y');
            }
            return array('level_id' => $parameters['level_id'],'user_id' => $parameters['user_id'],'error'=>'','score'=>'','solved'=>'n');
        }
        return array('level_id' => $parameters['level_id'],'user_id' => $parameters['user_id'],'error'=>'manipulation','score'=>'','solved'=>'');
    }

    protected function createNewGame() {
        return new $this->entityClass();
    }

}
