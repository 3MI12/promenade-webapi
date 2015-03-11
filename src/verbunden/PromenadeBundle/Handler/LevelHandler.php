<?php

namespace verbunden\PromenadeBundle\Handler;

use Doctrine\Common\Persistence\ObjectManager;
use verbunden\PromenadeBundle\Model\LevelInterface;

/**
 * LevelHandler
 *
 * @package verbunden\PromenadeBundle\Handler
 * @author Benjamin Brandt
 */
class LevelHandler implements LevelHandlerInterface {

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
     * List all level
     *
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @param int $limit  the limit of the result
     * @param int $offset starting from the offset
     * @return array
     */
    public function listLevel($limit = 15, $offset = 0) {
        return $this->repository->findBy(array(), null, $limit, $offset);
    }

    /**
     * Get one level given the identifier
     *
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @param integer $id
     * @return array
     */
    public function showLevel($level_id) {
        return $this->repository->findOneById($level_id);
    }

    /**
     * Check a level solution
     *
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @param integer $level_id
     * @param integer $id
     * @return bool
     */
    public function solveLevel($level_id, array $parameters) {
        // get level object:
        $game = $this->repository->findOneById($level_id);
        // prove that level exists:
        if ($game) {
            // prove the level solution:
            if ($level->getGrid() == $parameters['grid']) {
                return array('level_id' => $level_id, 'user_id' => 'guest', 'error' => '', 'score' => '0', 'solved' => true);
            }
            return array('level_id' => $level_id, 'user_id' => 'guest', 'error' => '', 'score' => '0', 'solved' => false);
        }
        return array('level_id' => $level_id, 'user_id' => 'guest', 'error' => 'manipulation', 'score' => '0', 'solved' => false);
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
    public function createLevel(array $parameters) {
        $name = $parameters['user']['name'];
        $accesstoken = $parameters['user']['accesstoken'];
        $level_id = $parameters['level']['id'];
        if ($name == 'admin' && UserHandler::verifyAccesstoken($name, $accesstoken)) {
            if (!$this->showLevel($level_id)) {
                $level = $this->buildLevel($parameters);
                $this->om->persist($level); //perist Data to Database
                $this->om->flush($level);
                return array('level_id' => $level_id, 'created' => true, 'error' => ' ');
            }
            return array('level_id' => $level_id, 'created' => false, 'error' => 'level_exists');
        }
        return array('level_id' => $level_id, 'created' => false, 'error' => 'invalid_accesstoken_or_user');
    }

    /*
     * build Level array $game
     * $game['grid'] is the solvedGrid
     * $game['startgrid'] is the startGrid
     * 
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @param array $parameters
     * @return array
     */

    private function buildLevel(array $parameters) {
        $game['color'] = array(); //init color array

        for ($i = 0; $i < 100; $i++) {
            if (isset($parameters['free'][$i])) {
                $game['grid'][$i] = $parameters['free'][$i];
                $game['startgrid'][$i] = array('color' => '#ffffff', 'edit' => true); // set default color #ffffff
                array_push($game['color'], $parameters['free'][$i]);
            } elseif (isset($parameters['set'][$i])) {
                $game['grid'][$i] = $parameters['set'][$i];
                $game['startgrid'][$i] = array('color' => $parameters['set'][$i], 'edit' => false);
            }
        }
        shuffle($game['color']); //randomize order of the color values
        $level = $this->createNewLevel(); // create new level object
        //set values
        $level->setId($parameters['level']['id']);
        $level->setColor($game['color']);
        $level->setComplexity($parameters['complexity']);
        $level->setGrid($game['grid']);
        $level->setStartgrid($game['startgrid']);
        return $level;
    }

    /**
     * Delete one level with the identifier
     *
     * @api
     *
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @param integer $level_id
     * @param $user_name
     * @param $accesstoken
     * @return bool
     */
    public function deleteLevel($level_id, $user_name, $accesstoken) {
        if ($user_name == 'admin' && UserHandler::verifyAccesstoken($user_name, $accesstoken)) {
            $level = $this->getDoctrine()->getRepository('verbundenPromenadeBundle:Level')->findOneById($level_id);
            $em->remove($level);
            $em->flush();
            return array('level_id' => $level_id, 'deleted' => false);
        }
        return array('level_id' => $level_id, 'deleted' => false);
    }

    /**
     * Create empty level object
     *
     * @api
     *
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @return \level
     */
    protected function createNewLevel() {
        return new $this->entityClass();
    }

}
