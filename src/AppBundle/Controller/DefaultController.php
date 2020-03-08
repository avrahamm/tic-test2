<?php

namespace AppBundle\Controller;

use AppBundle\Tic\Board;
use AppBundle\Tic\Game;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function indexAction(Request $request)
    {
        return $this->render(
            'AppBundle:Default:index.html.twig'
        );
    }

    public function startAction(int $size,string $mode)
    {
        $this->get('app.model.game')->startGame($size,$mode);
        $game = $this->get('app.model.game')->getGame();

        return $this->render(
            'AppBundle:Default:start.html.twig', array(
            'grid' => $game->getBoard()->getGrid(),
            'currentPlayer' => $game->getCurrentPlayer(),
            'size' => $size,
            'mode' => $mode,
        ));
    }

    public function playAction($row,$col)
    {
        $messages = array();
        $game = $this->get('app.model.game')->getGame();
        // prevent move from another tab when game is over.
        if($this->isGameOver($game)) {
            return $this->redirectToRoute('end');
        }

        if(!$game->isMoveLegal($row,$col)) {
            $messages = $this->getMessages($row,$col);
        } else {
            $game->makeMove($row,$col);
            $this->get('app.model.game')->setGame($game);
            if($this->isGameOver($game)) {
                return $this->redirectToRoute('end');
            }
        }

        return $this->render(
            'AppBundle:Default:play.html.twig', array(
            'row' => $row,
            'col' => $col,
            'messages' => $messages,
            'grid' => $game->getBoard()->getGrid(),
            'currentPlayer' => $game->getCurrentPlayer(),
            'size' => $game->getBoard()->getSize(),
            'mode' => $game->getMode(),
            'nextMoveLocation' => $this->getNextMoveLocation($game)
        ));
    }

    private function getNextMoveLocation(Game $game)
    {
        if( $game->getMode() == Game::HUMAN_MODE) {
            return null;
        }

        if( $game->getCurrentPlayer() == Board::X) {
            return '';
        }
        // generate next move for 'o'
        $nextGameStep = $game->getBoard()->getEmptySquare();
        $row = $nextGameStep[0];
        $col = $nextGameStep[1];
        $nextMoveLocation = $this->generateUrl('play', array('row' => $row, 'col' => $col));
        return $nextMoveLocation;
    }

    private function getMessages($row, $col)
    {
        $message = " $row-$col move is illegal";
        if( $row == Game::IN_PLAY_INDEX ){
            $message = 'Continue to play!';
        }
        else if( $row == Game::SAVE_GAME_INDEX ){
            $message = 'Game was saved successfully! Continue to play!';
        }
        else if( $row == Game::RESTORE_GAME_INDEX ){
            $message = 'Game was restored successfully! Continue to play!';
        }
        $messages []= $message;
        return $messages;
    }

    public function endAction()
    {
        $message = '';
        $game = $this->get('app.model.game')->getGame();
        $gameState = $game->getState();

        // sanity checks
        if ( Game::STATE_NEW == $gameState ) {
            return $this->redirectToRoute('start');
        }
        if ( Game::STATE_IN_PLAY == $gameState ) {
            return $this->redirectToRoute('play',
                array('row' => GAME::IN_PLAY_INDEX, 'col' => GAME::IN_PLAY_INDEX));
        }

        if(Game::STATE_TIE == $game->getState()) {
            $message = 'Game Over: tie! how boring!';
        } else {
            $message = 'Game Over: ' . $game->getWinner() . ' has won!';
        }

        return $this->render(
            'AppBundle:Default:end.html.twig', array(
            'message' => $message,
            'grid' => $game->getBoard()->getGrid(),
            'size' => $game->getBoard()->getSize()
        ));
    }

    private function isGameOver(Game $game)
    {
        return in_array($game->getState(), array(Game::STATE_TIE, Game::STATE_WON));
    }

    /**
     * Saves game from session to DB.
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function saveAction()
    {
        $game = $this->get('app.model.game')->getGame();
        $gameJson = $game->serialize();

        $connection = $this->get('database_connection');
        $connection->executeQuery("UPDATE game set json= '$gameJson' where title='last'");

        return $this->redirectToRoute('play',
            array('row' => Game::SAVE_GAME_INDEX,'col' => Game::SAVE_GAME_INDEX)
        );
    }

    /**
     * Restores game from DB to session.
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function restoreAction()
    {
        $connection = $this->get('database_connection');
        $res = $connection->fetchAll("SELECT json FROM game where title='last'");
        $gameJson = $res[0]['json'];
        $game = $this->get('app.model.game')->restoreGame($gameJson);

        return $this->redirectToRoute('play',
            array('row' => Game::RESTORE_GAME_INDEX,'col' => Game::RESTORE_GAME_INDEX)
        );
    }
}
