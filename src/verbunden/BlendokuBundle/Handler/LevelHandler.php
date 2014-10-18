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
class LevelHandler implements LevelHandlerInterface {

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
    public function listLevel($limit = 5, $offset = 0) {
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
    public function showLevel($level_id) {
        return $this->repository->findOneById($level_id);
    }

    /**
     * Verify one level given the parameters
     *
     * @api
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
        $game['color'] = array(); //init color array
        /*
         * build Level array $game
         * $game['grid'] is the solvedGrid
         * $game['startgrid'] is the startGrid
         */
        for ($i = 0; $i < 100; $i++) {
            if (isset($parameters['free'][$i])) {
                $game['grid'][$i] = $parameters['free'][$i];
                $game['startgrid'][$i] = array('color' => '#6b6b6b', 'edit' => true); // set default color #6b6b6b
                array_push($game['color'], $parameters['free'][$i]);
            } elseif (isset($parameters['set'][$i])) {
                $game['grid'][$i] = $parameters['set'][$i];
                $game['startgrid'][$i] = array('color' => $parameters['set'][$i], 'edit' => false);
            }
        }
        shuffle($game['color']); //randomize order of the color values
        $level = LevelHandler::createNewLevel(); // create new level object
        //set values
        $level->setId($parameters['level_id']);
        $level->setColor($game['color']);
        $level->setComplexity($parameters['complexity']);
        $level->setGrid($game['grid']);
        $level->setStartgrid($game['startgrid']);

        $this->om->persist($level); //perist Data to Database
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
    public function editLevel(array $parameters) {
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
    public function deleteLevel($level_id) {
        $level = $this->getDoctrine()->getRepository('verbundenBlendokuBundle:Level')->findOneById($level_id);
        $em->remove($level);
        $em->flush();
        return $level;
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
