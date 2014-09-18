<?php
namespace verbunden\BlendokuBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Game
 * 
 * Acts as cart and stores customer games
 *
 * @author Benjamin Brandt
 * @version 1.0
 *
 * @ORM\Entity
 * @ORM\Table(name="`game`")
 */
class Game {
	/**
	* @ORM\Id
	* @ORM\ManyToOne(targetEntity="User", inversedBy="id", cascade={"ALL"})
	* @var User who played the game
	**/
	private $user;
	
	/**
	* @ORM\Id
	* @ORM\ManyToOne(targetEntity="Level", inversedBy="id", cascade={"ALL"})
	* @var Level that the gamer has played
	**/
	private $level;
	
	/**
	* @ORM\Column(type="datetime")
	* @var start DateTime time of the game
	*/	
	private $starttime;
	
	/**
	* @ORM\Column(type="datetime")
	* @var end DateTime time of the game
	*/
	private $endtime;
	
	public function __construct() {
		$this->positions = new ArrayCollection();
	}
	
	/**
 	* get all games
	*
	* @param entityManager $em EntityManager instance
 	* @return Array element 'games' containing game data
 	*/
	public static function getHighscore() {
		$games = $em->getRepository('Game')->findAll();
		$data['games'] = array();
		foreach($games as $game) {
			$data['highscore'][$game->getId()] = $game->getGameData();
		};
		return $data;
	}

	/**
 	* get all games for the currently logged in user
	*
	* @param entityManager $em EntityManager instance
 	* @return Array element 'games' containing game data
 	*/
	public static function getAllByUser($em) {
		if(!$_SESSION['user']->getId()) {
			//$_SESSION['messages'][] = 'Sie müssen sich zuerst anmelden, um Ihre Bestellungen anzusehen!';
			$data['redirect'] = '/user/login/';
			return $data;
		}
		$games = $em->getRepository('Game')->findByUser($_SESSION['user']);
		$data['games'] = array();
		foreach($games as $game) {
			$data['games'][$game->getId()] = $game->getGameData();
		};
		return $data;
	}

	/**
 	* get game by id
	*
	* @param entityManager $em EntityManager instance
	* @param int $id game id
 	* @return Array element 'game' containing game data per user
 	*/
	public static function getByLevel($id) {
		$data = array();
		$game = $em->getRepository('verbundenBlendokuBundle:Game')->findByLevel($id);
		$data['game'] = $game->getGameData();
		return $data;
	}
	
	/**
 	* get games
	*
	* @param entityManager $em EntityManager instance
	* @param int $userId user id
 	* @return Array
 	*/
	public static function getByGamer($id) {
		return $em->getRepository('verbundenBlendokuBundle:Game')->findByUser($id);
	}
	
	/**
 	* start the game
	*
	* @param $user_id, $level_id
 	* @return Array containing 'success', 'error', 'starttime'
 	*/
	public function startGame($user_id, $level_id) {
		$data = array('success' => true, 'error' => array());
		return $data;
	}
	
	/**
 	* calculates price of game
	*
 	* @return Array containing entries 'articles', 'shipping' and 'total'
 	*/
	public function calcPrice() {
		$price['articles'] = 0;
		foreach($this->positions as $gameArticle) {
			$price['articles'] += $gameArticle->getPrice() * $gameArticle->getQuantity();
		}
		$price['shipping'] = $price['articles'] < SHIPPING_FREE_FROM ? SHIPPING_FEE : 0;
		$price['total'] = $price['articles'] + $price['shipping'];
		$this->price = $price['total'];
		return $price;
	}
	
	/**
 	* get quantity of an article in cart
	*
	* @param int $articleId
 	* @return float
 	*/
	public function getQuantityById($articleId) {
		$gameArticle = $this->positions->get($articleId);
		return $gameArticle ? $gameArticle->getQuantity() : 0;
	}
	
	/**
 	* get game data for display purposes
	*
 	* @return Array contains entries 'id', 'positions', 'price', 'user', 'gametime' and 'canceled'
 	*/
	public function getHighscoreArray() {
		return array(
			'user_id' => $this->user,
			'level_id' => $this->level,
			'points' => $this->calcScore(),
		);
	}
	
	/**
 	* persist shopping cart content to database
	*
	* Checks for each GameArticle placed in cart whether corresponding Article still is available in gameed quantity.
	* If this succeeds for all gameed articles, will then persist GameArticles with gameed quantity in database,
	* and will finally persist Game object as well. Clears Game object stored in session afterwards, so that cart will be empty again.
	*
	* Will issue an error for every gameed article that is not available in requested quantity any more otherwise.
	*
 	* @return Array will contain 'success', 'error' and possible 'redirect' instruction
 	*/
	public function finalize($em) {
		$data = array('success' => true, 'error' => array());
		if(!count($this->positions)) {
			$_SESSION['messages'][] = 'Ihr Warenkorb ist noch leer!';
			$data['redirect'] = '/cart/';
			return $data;
		}
		if(!$_SESSION['user']->getId()) {
			$_SESSION['messages'][] = 'Sie müssen sich zunächst anmelden!';
			$data['redirect'] = '/user/login/';
			return $data;
		}
		if(!$_SESSION['user']->ableToGame()) {
			$_SESSION['messages'][] = 'Um Bestellen zu können, müssen Sie zunächst ihr Profil vervollständigen!';
			$data['redirect'] = '/user/login/';
			return $data;
		}
		$this->user = User::getUserById($em, $_SESSION['user']->getId());
		foreach($this->positions as $gameArticle) {
			$article = Article::getById($em, $gameArticle->getArticleId());
			if($gameArticle->getQuantity() > $article->getInventory()) {
				$data['success'] = false;
				$data['error'][] = 'Von Artikel ' . $article->getName() . ' sind nur noch ' . $article->getInventory() . ' Stück auf Lager!';
				$gameArticle->setQuantity($article->getInventory());
			}
		}
		if($data['success']) {
			foreach($this->positions as $gameArticle) {
				$article = Article::getById($em, $gameArticle->getArticleId());
				$article->setInventory($article->getInventory() - $gameArticle->getQuantity());
				$em->persist($article, $gameArticle);
			}
						$this->gametime = new DateTime();
						$this->canceled = false;
						$em->persist($this->user);
						$em->persist($this);
						$em->flush();
						$_SESSION['messages'][] = 'Bestellung ausgeführt!';
						$_SESSION['game'] = new Game();
						sendGameConfirmMail($this, $this->user);		
		}
		else {
			$errors = $data['error'];
			$success = $data['success'];
			$data = $this->getGameData();
			$data['error'] = $errors;
			$data['success'] = $success;
		}
		return $data;
	}
	
	/**
 	* cancel an existing game
	*
	* Cancels an existing game. Will add gameed quantity of each GameArticle back onto corresponding Article's inventory.
	*
	* @param entityManager $em EntityManager instance
 	* @return array will containg 'redirect' to game list
 	*/
	public function cancel($em) {
		if(!$_SESSION['user']->checkAdmin()) {
			$_SESSION['messages'][] = 'Sie müssen als Administrator angemeldet sein, um Bestellungen stornieren zu können!';
			return;
		}
		if(!$this->canceled) {
			foreach($this->positions as $gameArticle) {
				$article = Article::getById($em, $gameArticle->getArticleid());
				$article->setInventory($article->getInventory() + $gameArticle->getQuantity());
				$em->persist($article);
			}
			$this->canceled = true;
			$em->persist($this);
			$em->flush();
			$_SESSION['messages'][] = 'Bestellung storniert!';
		}
		$data['redirect'] = '/game/list/';
		return $data;
	}
	
	/**
 	* get property $id
	*
 	* @return int 
 	*/
	public function getId() {
		return $this->id;
	}
	
	/**
 	* get property $user
	*
 	* @return User 
 	*/
	public function getUser() {
		return $this->user;
	}
	

    /**
     * Set starttime
     *
     * @param \DateTime $starttime
     * @return Game
     */
    public function setStarttime($starttime)
    {
        $this->starttime = $starttime;

        return $this;
    }

    /**
     * Get starttime
     *
     * @return \DateTime 
     */
    public function getStarttime()
    {
        return $this->starttime;
    }

    /**
     * Set endtime
     *
     * @param \DateTime $endtime
     * @return Game
     */
    public function setEndtime($endtime)
    {
        $this->endtime = $endtime;

        return $this;
    }

    /**
     * Get endtime
     *
     * @return \DateTime 
     */
    public function getEndtime()
    {
        return $this->endtime;
    }

    /**
     * Set user
     *
     * @param \verbunden\BlendokuBundle\Entity\User $user
     * @return Game
     */
    public function setUser(\verbunden\BlendokuBundle\Entity\User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Set level
     *
     * @param \verbunden\BlendokuBundle\Entity\Level $level
     * @return Game
     */
    public function setLevel(\verbunden\BlendokuBundle\Entity\Level $level)
    {
        $this->level = $level;

        return $this;
    }

    /**
     * Get level
     *
     * @return \verbunden\BlendokuBundle\Entity\Level 
     */
    public function getLevel()
    {
        return $this->level;
    }
}
