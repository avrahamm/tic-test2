<?php
/**
 * Created by PhpStorm.
 * User: ami
 * Date: 10/29/15
 * Time: 12:30 PM
 */

namespace AppBundle\Model;


use AppBundle\Tic\Game;
use Symfony\Component\HttpFoundation\Session\Session;

class GameModel
{
    /** @var  Session */
    private $session;

    /** @var  Game */
    private $game;

    /**
     * GameModel constructor.
     * @param Session $session
     */
    public function __construct(Session $session)
    {
        $this->session = $session;
        $this->loadGame();
        $this->storeGame();
    }

    /**
     * @return Game
     */
    public function getGame()
    {
        return $this->game;
    }

    /**
     * @param Game $game
     */
    public function setGame($game)
    {
        $this->game = $game;
        $this->storeGame();
    }

    private function loadGame()
    {
        $json = $this->session->get('game', $this->emptyGameJson());
        $game = new Game();
        $game->unserialize($json);
        $this->game = $game;
        return $this->game;
    }

    private function storeGame()
    {
        $this->session->set('game', $this->game->serialize());
    }

    private function emptyGameJson()
    {
        $game = new Game();
        $game->start();
        return $game->serialize();
    }

    public function startGame($size = 3)
    {
        $this->game->start($size);
        $this->storeGame();
    }

    public function restoreGame(string $gameJson)
    {
        $this->session->set('game',$gameJson );
    }
}