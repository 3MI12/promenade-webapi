<?php

namespace verbunden\PromenadeBundle\Handler;

use Doctrine\Common\Persistence\ObjectManager;
use verbunden\PromenadeBundle\Model\GameInterface;
use verbunden\PromenadeBundle\Model\LevelInterface;

/**
 * GameHandler
 *
 * @package verbunden\PromenadeBundle\Handler
 * @author Benjamin Brandt
 */
class GameHandler implements GameHandlerInterface {

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
     * List level with userscore
     *
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @param string $username
     * @param string $accesstoken
     * @param string $limit
     * @param string $offset
     * @return array queryresult
     */
    public function listGames($username, $accesstoken, $offset = 0, $limit = 15) {
        $result = array();
        if ($username && $accesstoken) {
            $query = $this->om->createQueryBuilder('game')
                ->addSelect('level.id AS id')
                ->addSelect('user.name AS name')
                ->addSelect('game.score AS level_score')
                ->from('verbundenPromenadeBundle:Game game')
                ->leftJoin('game.user', 'user')
                ->andWhere("user.name = :username")
                ->andWhere('user.accesstoken = :accesstoken')
                ->setParameter('username', $username)
                ->setParameter('accesstoken', $accesstoken)
                ->leftJoin('game.level', 'level')
                ->orderBy('id', 'ASC')
                ->setFirstResult($offset)
                ->setMaxResults($limit)
                ->getQuery();
        $result = $query->getResult();
        }
        return $result;
    }

    /**
     * Start a level
     *
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @param array $parameters
     * @return array game object
     */
    public function startGame(array $parameters) {
        $name = $this->postedNameLen($parameters['user']['name']);
        $id = $parameters['level']['id'];
        $accesstoken = $parameters['user']['accesstoken'];
        if (!$name || !$accesstoken) {
            return array('error' => 'no_input', 'name' => $name, 'accesstoken' => $accesstoken);
        }
        $user = $this->om->getRepository('verbundenPromenadeBundle:User')->findOneByName($name);
        $level = $this->om->getRepository('verbundenPromenadeBundle:Level')->findOneById($id);
        $game = $this->repository->findOneBy(array('user' => $name, 'level' => $id));
        if ($user->getAccesstoken() !== $accesstoken) {
            return array('error' => 'auth_failed'); //,'name'=>$name, 'accesstoken'=>$accesstoken, 'accesstokendb'=>$user->getAccesstoken());
        }
        if (!$game) {
            $game = gameHandler::createNewGame();

            $game->setUser($user);
            $game->setLevel($level);
            $game->setScore('0');
            if (!$level) {
                return array('error' => 'no_level');
            }
            if (!$user) {
                return array('error' => 'no_user');
            }
        }
        $game->setStarttime(time());
        $this->om->persist($game);
        $this->om->flush($game);
        return $game;
    }

    /**
     * Solve a level
     *
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @param array $parameters
     * @return array status
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
        if ($game) {
            // prove the user access:
            if ($game->getUser()->getAccesstoken() !== $accesstoken) { // validate the game session:
                $return['error'] = 'invalid_accesstoken';
                return $return;
            }
            // prove that starttime match with game session:
            if (strval($game->getStarttime()) !== $parameters['starttime']) {
                $return['error'] = 'invalid_starttime';
                return $return;
            }
            $level = $game->getLevel();
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
            $return['error'] = 'found_no_game';
            return $return;
        }
    }

    /**
     * get the global High Score
     *
     * @api
     *
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @param  integer $limit
     * @param  integer $offset
     * @return array 
     */
    public function highScore($limit = 10, $offset = 0) {
        $query = $this->om->createQueryBuilder('game')
                ->addSelect('user.name')
                ->addSelect('SUM(game.score) AS user_score')
                ->addSelect('count(user.name) AS played_level')
                ->from('verbundenPromenadeBundle:Game game')
                ->leftJoin('game.user', 'user')
                ->groupBy('game.user')
                ->orderBy('user_score', 'DESC')
                ->setFirstResult($offset)
                ->setMaxResults($limit)
                ->getQuery();
        return $query->getResult();
    }

    /**
     * get the score of one user
     *
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @param  string $username
     * @return integer 
     */
    public function userScore($username) {
        if ($username) {
            $query = $this->om->createQueryBuilder('game')
                    ->addSelect('user.name')
                    ->addSelect('SUM(game.score) AS user_score')
                    ->addSelect('count(user.name) AS played_level')
                    ->from('verbundenPromenadeBundle:Game game')
                    ->where('user.name =:username')
                    ->setParameter('username', $username)
                    ->leftJoin('game.user', 'user')
                    ->groupBy('game.user')
                    ->orderBy('user_score', 'DESC')
                    ->getQuery();
            $result = $query->getResult();
        }
        return $result;
    }

    /**
     * calculate score
     *
     * @author Martin Kuntizsch 2014
     * @version 1.0
     * @param  integer $starttime
     * @param  integer $endtime
     * @param  integer $complexity
     * @return array 
     */
    public function calculateGameScore($starttime, $endtime, $complexity) {
        $data = array();
        if ($starttime && $endtime && $complexity) {
            $data['maxtime'] = 30 * $complexity;
            $data['timediff'] = $endtime - $starttime;
            $data['score'] = $data['maxtime'] - $data['timediff'];
            $data['score'] = ($data['score'] >= 0) ? $data['score'] : 0;
        }
        return $data;
    }

    /**
     * check posted username
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
        return $string;
    }

}
