<?php
/**
 * Created by PhpStorm.
 * User: ami
 * Date: 10/29/15
 * Time: 10:02 AM
 */

namespace AppBundle\Tic;


class Board
{
    private $grid;
    private $size;

    const NOTHING = '';
    const O = 'o';
    const X = 'x';
    const VICTORY = 'victory';

    /**
     * Board constructor.
     */
    public function __construct($size)
    {
        $this->size = $size;
        $this->initGrid();
        $this->clear();
    }

    private function initGrid()
    {
        for($i = 0; $i < $this->size; $i++) {
            $this->grid[] = array();
        }
    }

    public function clear()
    {
        for($i = 0; $i < $this->size; $i++) {
            for($j = 0; $j < $this->size; $j++) {
                $this->setSquare($i, $j, self::NOTHING);
            }
        }
    }

    public function areDimensionsLegal($row, $col)
    {
        return ( ($row >= 0 && $row < $this->size) && ($col >= 0 && $col < $this->size));
    }

    public function getSquare($row, $col)
    {
        $cell = &$this->grid[$row][$col];
        return $cell['val'];
    }

    public function setSquare($row, $col, $val)
    {
        $this->grid[$row][$col] = array('val'=> $val, 'status' =>'');
        return $this->getSquare($row, $col);
    }

    public function isFull()
    {
        for($i = 0; $i < $this->size; $i++) {
            for($j = 0; $j < $this->size; $j++) {
                if(self::NOTHING == $this->getSquare($i, $j)) {
                    return false;
                }
            }
        }
        return true;
    }

    public function isEmpty()
    {
        for($i = 0; $i < $this->size; $i++) {
            for($j = 0; $j < $this->size; $j++) {
                if(self::NOTHING != $this->getSquare($i, $j)) {
                    return false;
                }
            }
        }
        return true;
    }

    public function getEmptySquare()
    {
        for($i = 0; $i < $this->size; $i++) {
            for($j = 0; $j < $this->size; $j++) {
                if(self::NOTHING == $this->getSquare($i, $j)) {
                    return array($i, $j);
                }
            }
        }
        return null;
    }

    public function loadBoard($grid)
    {
        $this->grid = $grid;
    }

    public function isBoardWon()
    {
        $res = false;
        for($i = 0; $i < $this->size; $i++) {
            $res = $res || $this->isColWon($i) || $this->isRowWon($i);
            if( $res) {
                return $res;
            }
        }
        $res = $res || $this->isMainDiagonWon();
        $res = $res || $this->isSecondDiagonWon();
        return $res;
    }

    private function setSquareStatus($row,$col,$status)
    {
        $cell = &$this->grid[$row][$col];
        $cell['status'] = $status;
    }

    public function isRowWon($row)
    {
        $square = $this->getSquare($row, 0);
        if(self::NOTHING == $square) {
            return false;
        }
        for($i = 1; $i < $this->size; $i++) {
            if($square != $this->getSquare($row, $i)) {
                return false;
            }
        }

        // set winning cells status
        for($i = 0; $i < $this->size; $i++) {
            $this->setSquareStatus($row, $i,Board::VICTORY);
        }
        return true;
    }

    public function isColWon($col)
    {
        $square = $this->getSquare(0, $col);
        if(self::NOTHING == $square) {
            return false;
        }
        for($i = 1; $i < $this->size; $i++) {
            if($square != $this->getSquare($i, $col)) {
                return false;
            }
        }

        // set winning cells status
        for($i = 0; $i < $this->size; $i++) {
            $this->setSquareStatus($i, $col,Board::VICTORY);
        }

        return true;
    }

    public function isMainDiagonWon()
    {
        $square = $this->getSquare(0, 0);
        if(self::NOTHING == $square) {
            return false;
        }
        for($i = 1; $i < $this->size; $i++) {
            if($square != $this->getSquare($i, $i)) {
                return false;
            }
        }

        // set winning cells status
        for($i = 0; $i < $this->size; $i++) {
            $this->setSquareStatus($i, $i,Board::VICTORY);
        }
        return true;
    }

    public function isSecondDiagonWon()
    {
        //To fix second diagonal indices
        $row = 0;
        $col = $this->size - 1;
        $square = $this->getSquare($row, $col);
        if(self::NOTHING == $square) {
            return false;
        }
        $row++;
        $col--;
        for(;$col >= 0; $row++,$col--) {
            if($square != $this->getSquare($row, $col)) {
                return false;
            }
        }

        // set winning cells status
        $row = 0;
        $col = $this->size - 1;
        for(;$col >= 0; $row++,$col--) {
            $this->setSquareStatus($row, $col,Board::VICTORY);
        }

        return true;
    }

    /**
     * @return mixed
     */
    public function getGrid()
    {
        return $this->grid;
    }

    /**
     * @return mixed
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @param mixed $size
     */
    public function setSize($size)
    {
        $this->size = $size;
    }


}