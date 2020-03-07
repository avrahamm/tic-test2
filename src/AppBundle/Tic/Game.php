<?php
/**
 * Created by PhpStorm.
 * User: ami
 * Date: 10/29/15
 * Time: 10:01 AM
 */

namespace AppBundle\Tic;


class Game
{
    /** @var  Board */
    private $board;

    private $currentPlayer;

    const STATE_NEW = 0;
    const STATE_IN_PLAY = 1;
    const STATE_TIE = 2;
    const STATE_WON = 3;
    const SAVE_GAME_INDEX = 10001;
    const RESTORE_GAME_INDEX = 10002;
    const IN_PLAY_INDEX = 10000;

    public function start($size=3)
    {
        $this->board = new Board($size);
        $this->currentPlayer = Board::X;
    }

    public function isMoveLegal($row, $col)
    {
        // added dimensions check
        return $this->areDimensionsLegal($row,$col) &&
            $this->isCellEmpty($row, $col);
    }

    public function areDimensionsLegal($row, $col)
    {
        return $this->board->areDimensionsLegal($row, $col);
    }

    public function isCellEmpty($row, $col)
    {
        return Board::NOTHING == $this->board->getSquare($row, $col);
    }

    public function makeMove($row, $col)
    {
        $this->board->setSquare($row, $col, $this->currentPlayer);
        $this->switchPlayer();
    }

    public function getState()
    {
        if($this->board->isEmpty()) {
            return self::STATE_NEW;
        }
        if($this->isGameWon()) {
            return self::STATE_WON;
        }
        if($this->isGameTie()) {
            return self::STATE_TIE;
        }
        return self::STATE_IN_PLAY;
    }

    private function isGameWon()
    {
        return $this->board->isBoardWon();
    }

    private function isGameTie()
    {
        return !$this->board->isBoardWon() && $this->board->isFull();
    }

    public function getWinner()
    {
        if(self::STATE_WON == $this->getState()) {
            $this->switchPlayer();
            $res = $this->currentPlayer;
            $this->switchPlayer();
            return $res;
        }
        return Board::NOTHING;
    }

    private function switchPlayer()
    {
        if(Board::X == $this->currentPlayer) {
            $this->currentPlayer = Board::O;
        } else {
            $this->currentPlayer = Board::X;
        }
    }

    /**
     * @param Board $board
     */
    public function setBoard($board)
    {
        $this->board = $board;
    }

    /**
     * @return Board
     */
    public function getBoard()
    {
        return $this->board;
    }

    /**
     * @return mixed
     */
    public function getCurrentPlayer()
    {
        return $this->currentPlayer;
    }

    /**
     * @param mixed $currentPlayer
     */
    public function setCurrentPlayer($currentPlayer)
    {
        $this->currentPlayer = $currentPlayer;
    }


    public function serialize()
    {
        $res = array(
            'grid' => $this->board->getGrid(),
            'size' => $this->board->getSize(),
            'currentPlayer' => $this->currentPlayer
        );

        return json_encode($res);
    }

    public function unserialize($json)
    {
        $this->start();
        $data = json_decode($json, true);
        $this->board->setSize($data['size']);
        $this->board->loadBoard($data['grid']);
        $this->currentPlayer = $data['currentPlayer'];
    }

}