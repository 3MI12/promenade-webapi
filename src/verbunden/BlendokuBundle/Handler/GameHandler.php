<?php

namespace verbunden\BlendokuBundle\Handler;

use Doctrine\Common\Persistence\ObjectManager;
use verbunden\BlendokuBundle\Model\GameInterface;
use verbunden\BlendokuBundle\Model\LevelInterface;
use verbunden\BlendokuBundle\Form\GameType;
use verbunden\BlendokuBundle\Exception\InvalidFormException;
use Symfony\Component\Form\FormFactoryInterface;

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
    public function __construct(ObjectManager $om, $entityClass, FormFactoryInterface $formFactory){
        $this->om = $om;
        $this->entityClass = $entityClass;
        $this->repository = $this->om->getRepository($this->entityClass);
        $this->formFactory = $formFactory;
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
        $game->setStarttime(time());
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
     * @param integer $level_id
     * @return array
     */
    public function solveGame($level_id, array $parameters) {
        // get game session object:
        $game = $this->repository->findOneBy(array('user' => $parameters['user']['id'], 'level' => $level_id));
        // get level session object:
        $level = $game->getLevel();
        // prove that starttime match with game session:
        if ($game && $game->getStarttime() == $parameters['starttime']) {
            // prove the level solution:
            if ($level->getGrid() == $parameters['grid']) {
                $time=time();
                // get the level score:
                $stats=$this->calculateGameScore($game->getStarttime(), $time,  $level->getComplexity());
                $stats['old_score']=$game->getScore();
                if($stats['old_score']<=$stats['score']){
                    $game->setEndtime($time);
                    $game->setScore($stats['score']);
                    $this->om->persist($game);
                    $this->om->flush($game);
                }
                return array('level_id' => $level_id, 'user_id' => $parameters['user']['id'], 'error' => '', 'score' => $stats['score'], 'solved' => true);
            }
            return array('level_id' => $level_id, 'user_id' => $parameters['user']['id'], 'error' => '', 'score' => '', 'solved' => false);
        }
        return array('level_id' => $level_id, 'user_id' => $parameters['user']['id'], 'error' => 'manipulation', 'score' => '', 'solved' => false);
    }
    
    /**
     * Create new game object
     *
     * @api
     *
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @return \game
     */
    protected function createNewGame() {
        return new $this->entityClass();
    }
    
    /**
     * calculate score
     *
     * @api
     *
     * @author Martin Kuntizsch 2014
     * @version 1.0
     * @param  integer $starttime
     * @param  integer $endtime
     * @param  integer $complexity
     * @return integer 
     */
    public function calculateGameScore($starttime, $endtime, $complexity) {
        $data['maxtime'] = 30 * $complexity;
        $data['timediff'] = $endtime - $starttime;
        $data['score'] = $data['maxtime'] - $data['timediff'];
        $data['score'] = ($data['score']>=0) ? $data['score']:0;
        return $data;
    }
}
