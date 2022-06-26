<?php

namespace board;

use pieces\Piece;

include __DIR__ . '/Pieces.php';

class Board
{
    protected $board;

    public function getBoard()
    {
        return $this->board;
    }


    /**
     * Returns the board with all pieces from the opponent
     * hidden
     * @param $playerid string The current players' id.
     * @return Piece[][]|NULL[][] The board with the other players pieces hidden.
     */
    public function getBoardForPlayer($playerid)
    {
        $orig_board = $this->getBoard();
        $board = array();
        for ($y = 0; $y < 10; $y++) {
            $row = array();
            for ($x = 0; $x < 10; $x++) {
                if (is_null($orig_board[$y][$x])) {
                    $row[$x] = NULL;
                } elseif ($orig_board[$y][$x] === "WATER") {
                    $row[$x] = "WATER";
                } else {
                    // It is a Piece
                    $curPiece = $orig_board[$y][$x];
                    if ($curPiece->getOwnerId() === $playerid) {
                        $row[$x] = $curPiece;
                    } else {
                        $row[$x] = "UNKNOWN";
                    }
                }
            }
            $board[$y] = $row;
        }

        return $board;
    }

    public function getBoardForBlue()
    {
        $current = $this->board;
        $reverse = array();
        for ($y = 9; $y >= 0; $y--)
        {
            $row = array();
            for ($x = 9; $x >= 0; $x--) {
                $row[] = $current[$y][$x];
            }
            $reverse[] = $row;
        }
        return $reverse;
    }

    public function getPositionOnBoard(int $y, int $x)
    {
       return $this->board[$y][$x] ;
    }


    /**
     * Creates the 10x10 grid that is used as board;
     * @return void
     */
    private function createBoard()
    {
        $board = array();
        for ($y = 0; $y < 10; $y++) {
            $row = array();
            for ($x = 0; $x < 10; $x++) {
                $row[$x] = NULL;
                // Add water tiles
                if ($y === 4 or $y === 5) {
                   if ($x === 2 or $x === 3 or $x === 6 or $x === 7) {
                       $row[$x] = 'WATER';
                   }
                }
            }
            $board[$y] = $row;
        }

        $this->board = $board;

    }

    public function __construct()
    {
        $this->createBoard();
    }

    /**
     * Set the given piece to the current location, if the location is occupied
     * check if the given piece can hit that piece.
     *
     * Note: this method does not check if the piece can actually move there (based on its own
     * movement constraints) this is so that this function also can be used to do the initial placing
     * of pieces.
     *
     * @param Piece $piece The piece that should be assigned to the place.
     * @param int $x The x coordinate of the board.
     * @param int $y The y coordinate of the board.
     * @param bool $force If true, the move is forced.
     * @return array[bool, string] If the piece could be set there and an error message if it could not be done.
     *
     */
    public function setPieceOnPosition(Piece $piece, int $y, int $x, bool $force = false)
    {

        if (is_null($this->board[$y][$x])) {
            $this->board[$y][$x] = $piece;
            return [true, 'Successfull move!'];
        }

        // Make sure that players cannot place pieces on water
        if ($this->board[$y][$x] === 'WATER') {
            return [false, "You cannot move in the water."];
        }

        $currentPiece = $this->board[$y][$x];
        if (!$force){
            // Cannot put your piece on your own piece unless forced.
            if ($currentPiece->getOwnerId() === $piece->getOwnerId()) {
                return [false, "You cannot hit a piece of your own!"];
            }

            if ($piece->canHit($currentPiece)) {
                $this->board[$y][$y] = $piece;
                return [true, 'Successfull move!'];
            }
        } else {
            $this->board[$y][$y] = $piece;
            return [true, 'Successfull move!'];
        }

        return [false, "Unkown error occured"];
    }


    /**
     * Move the piece that is located at (cur_y, cur_x) up with the given distance (default: 1).
     *
     * @param int $cur_y The current y position of the piece that wants to move.
     * @param int $cur_x The current x position of the piece that wants to move.
     * @param int $distance The distance the piece wants to move with (default 1 -- only the scout moves more).
     * @return bool If the piece can move up with the given distance.
     */
    public function moveUp(int $cur_y, int $cur_x, int $distance = 1) : array
    {

        $currentPiece = $this->board[$cur_y][$cur_x];
        // If the current piece is null it means that is has not been set yet.
        if (is_null($currentPiece)) {
            return [false, "You cannot move when there is no piece!"];
        }

        // Test if moving up will move out of bounds.
        if ($cur_y - $distance < 0) {
            // TOP is index 0.
            return [false, "You canont move outside of the board!"];
        }

        // Check if the current piece can move the given distance (1 by default).
        if(!$currentPiece->canMoveDistance($distance)) {
           return [false, "You cannot move this distance!"];
        }

        list($canMove, $msg) = $this->setPieceOnPosition($currentPiece, $cur_y-$distance, $cur_x);
        // If the piece is moved make it's previous position available.
        if ($canMove) {
            $this->board[$cur_y][$cur_x] = NULL;
        }
        return [$canMove, $msg];

    }

    /**
     * Move the piece that is located at (cur_x, cur_x) down with the given distance (default: 1).
     *
     * @param int $cur_y The current y position of the piece that wants to move.
     * @param int $cur_x The current x position of the piece that wants to move.
     * @param int $distance The distance the piece wants to move with (default 1 -- only the scout moves more).
     * @return bool If the piece can move down with the given distance.
     */
    public function moveDown(int $cur_y, int $cur_x, int $distance = 1) : array
    {
        $currentPiece = $this->board[$cur_y][$cur_x];

        if (is_null($currentPiece)) {
            return [false, "No piece to move."];
        }

        // Check if there is room at the bottom to move to (not out of bounds)
        if ($cur_y + $distance >= 10) {
            return [false, "You cannot move outside the board."];
        }

        if (!$currentPiece->canMoveDistance($distance)) {
            return [false, "You cannot move this distance."];
        }

        list($canMove, $msg) = $this->setPieceOnPosition($currentPiece, $cur_y+$distance, $cur_x);
        // If the piece is moved make it's previous position available.
        if ($canMove) {
            $this->board[$cur_y][$cur_x] = NULL;
        }
        return [$canMove, $msg];
    }

    /**
     * Move the piece that is located at (cur_x, cur_x) right with the given distance (default: 1).
     *
     * @param int $cur_y The current y position of the piece that wants to move.
     * @param int $cur_x The current x position of the piece that wants to move.
     * @param int $distance The distance the piece wants to move with (default 1 -- only the scout moves more).
     * @return bool If the piece can move right with the given distance.
     */
    public function moveRight(int $cur_y, int $cur_x, int $distance = 1) : array
    {

        $currentPiece = $this->board[$cur_y][$cur_x];

        if (is_null($currentPiece)) {
            return [false, "No piece to move."];
        }

        // Check if there is room at the bottom to move to (not out of bounds)
        if ($cur_y + $distance >= 10) {
            return [false, "You cannot move outside the board."];
        }

        if (!$currentPiece->canMoveDistance($distance)) {
            return [false, "You cannot move this distance."];
        }

        list($canMove, $msg) = $this->setPieceOnPosition($currentPiece, $cur_y, $cur_x + $distance);
        // If the piece is moved make it's previous position available.
        if ($canMove) {
            $this->board[$cur_y][$cur_x] = NULL;
        }
        return [$canMove, $msg];
    }

    /**
     * Move the piece that is located at (cur_x, cur_x) left with the given distance (default: 1).
     *
     * @param int $cur_y The current y position of the piece that wants to move.
     * @param int $cur_x The current x position of the piece that wants to move.
     * @param int $distance The distance the piece wants to move with (default 1 -- only the scout moves more).
     * @return bool If the piece can move left with the given distance.
     */
    public function moveLeft(int $cur_y, int $cur_x, int $distance = 1) : array
    {

        $currentPiece = $this->board[$cur_y][$cur_x];

        if (is_null($currentPiece)) {
            return [false, "No piece to move!"];
        }

        // Check if there is room to move to the right
        if ($cur_x - $distance < 0) {
            return [false, "You cannot move outside of the board."];
        }

        if (!$currentPiece->canMoveDistance($distance)) {
            return [false, "You cannot move this distance!"];
        }


        list($canMove, $msg) = $this->setPieceOnPosition($currentPiece, $cur_y, $cur_x - $distance);
        // If the piece is moved make it's previous position available.
        if ($canMove) {
            $this->board[$cur_y][$cur_x] = NULL;
        }
        return [$canMove, $msg];
    }


    public static function fromJson($json)
    {
        $arr_board = NULL;
        if (is_array($json)) {
            $arr_board = $json;
        } else {
            $arr_board = json_decode($json);
        }

        $board = new static();

        for ($y =0; $y < 10; $y++) {
            for ($x = 0; $x < 10; $x++) {
                if (!is_null($arr_board[$y][$x]) && $arr_board[$y][$x] !== 'WATER') {
                    $piece = \pieces\Piece::fromJson($arr_board[$y][$x]);
                    $board->setPieceOnPosition($piece, $y, $x, true);
                }
            }
        }

        return $board;

    }



}
