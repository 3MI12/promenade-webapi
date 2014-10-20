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
    public function __construct(ObjectManager $om, $entityClass, FormFactoryInterface $formFactory) {
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
        $name = $this->postedNameLen($parameters['user']['name']);
        $id = $parameters['level']['id'];
        $accesstoken = $parameters['user']['accesstoken'];
        if(!$name || !$accesstoken){
            return array('error' => 'no_input');
        }
        $user = $this->om->getRepository('verbundenBlendokuBundle:User')->findOneByName($name);
        $level = $this->om->getRepository('verbundenBlendokuBundle:Level')->findOneById($id);
        $game = $this->repository->findOneBy(array('user' => $name, 'level' => $id));
        if($user->getAccesstoken() !== $accesstoken){
            return array('error' => 'auth_failed');
        }
        if (!$game) {
            $game = gameHandler::createNewGame();
            
            $game->setUser($user);
            $game->setLevel($level);
            if (!$level){
                return array('error' => 'no_level');
            }
            if (!$user){
                return array('error' => 'no_user');
            }
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
     * @param integer $level_id
     * @param array $parameters
     * @return array
     */
    public function solveGame(array $parameters) {
        $this->postedNameLen($parameters['user']['name']);
        $name = $this->postedNameLen($parameters['user']['name']);
        $accesstoken = $parameters['user']['accesstoken'];
        $id = $parameters['level']['id'];
        // get game session object:
        $game = $this->repository->findOneBy(array('user' => $name, 'level' => $id));
        //build the return message
        $return['level_id'] = $id;
        $return['name'] = $name;
        $return['error'] = '';
        $return['score'] = 0;
        $return['solved'] = false;
        ////////////// TEST:
        $parameters['starttime'] =$game->getStarttime();
        ////////////// TEST    
        
        
        // prove that starttime match with game session:
        if ($game) {
            // prove the user access:
            if ($game->getUser()->getAccesstoken() !== $accesstoken) { // validate the game session:
                $return['error'] = 'invalid_accesstoken'.$game->getUser()->getAccesstoken().'<->'.$accesstoken;
                return $return;
            }
            if($game->getStarttime() !== $parameters['starttime']) {
                $return['error'] = 'invalid_starttime'.$game->getStarttime().'='.$parameters['starttime'];
                return $return;
            }
            if ($level->getGrid() == $parameters['grid']) { // prove the level solution:
                $time = time();
                // get the level score:
                $stats = $this->calculateGameScore($game->getStarttime(), $time, $level->getComplexity());
                $stats['old_score'] = $game->getScore();
                if ($stats['old_score'] <= $stats['score']) {
                    $game->setEndtime($time);
                    $game->setScore($stats['score']);
                    $this->om->persist($game);
                    $this->om->flush($game);
                }
                $return['score'] = $stats['score'];
                $return['solved'] = true;
            }
            return $return;
        } else {
            $level = $this->om->getRepository('verbundenBlendokuBundle:Level')->findOneById($parameters['level']);
            if (!$level) {
                $return['level_id'] = '';
                $return['error'] = 'no_level';
            } elseif ($level->getGrid() !== $parameters['grid']) {
                $return['solved'] = false;
            }
            return $return;
        }
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
        $data['score'] = ($data['score'] >= 0) ? $data['score'] : 0;
        return $data;
    }

     /**
     * calculate User Score
     *
     * @api
     *
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @param  string $username
     * @return integer 
     */
    public function calculateUserScore($username) {
        $data = $this->om->getRepository('verbundenBlendokuBundle:game')->findByUser($username);
        $userscore = 0;
        foreach ($data as $value) {
            $userscore += $value->getScore(); 
        }
        return array('username'=>$username,'totalscore'=>$userscore);
    }
    
     /**
     * calculate High Score
     *
     * @api
     *
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @param  string $username
     * @return integer 
     */
    public function calculateHighScore() {
        return array('hmhm');
        $result = $this->createQueryBuilder('qb')
            ->select("avg(qb.score) as avg, count(qb.score) as count")
            ->where('g.idPlayer = :idPlayer')
            ->groupBy('qb.Name')
            ->setParameter('idPlayer', $id)
            ->orderBy('stat_sum_realised', 'DESC')
            ->getQuery();
        return $result;
        
        $q = $this->om->createQueryBuilder();
        $q->select(['game.user'])
          ->addSelect('SUM(game.score) AS HIDDEN stat_sum_realised')
          ->from('Entity\Game', 'game')
          ->groupBy('game.user');
        $q->orderBy('stat_sum_realised', 'DESC');
        
        $users = $this->om->getRepository('verbundenBlendokuBundle:user')->findAll();
        $output = array();
        foreach ($users as $user) {
            array_push($output, $this->calculateUserScore($user->getName()));
        }
        array_multisort($output, SORT_DESC, $userscore, SORT_ASC, $output);
        return $output;
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
        //if ($string == 'admin' || $string == 'guest'){
        //    $string = NULL;
        //}
        return $string;
    }
    
}
