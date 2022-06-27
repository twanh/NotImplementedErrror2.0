<?php

namespace board;

use pieces\Piece;

include __DIR__ . '/Pieces.php';

class Board
{
    protected $board;

    /**
     * @return mixed
     */
    public function getBoard()
    {
        return $this->board;
    }


    /**
     * Returns the board with all pieces from the opponent
     * hidden (they are shown as "UNKNOWN").
     *
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
                    // When the owner id of a piece is not the $playerid the piece
                    // gets replaced with "UNKNOWN".
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


    /**
     * Returns what value is stored on the board on the given coordinates.
     * @param int $y The y coordinate.
     * @param int $x The x coordinate.
     * @return Piece|string|null The value that is on the given coordinates..
     */
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
     * @param int|NULL $cur_y  The current y position of the piece.
     * @param int|NULL $cur_x  The current x position of the piece.
     * @return array[bool|string] If the piece could be set there and an error message if it could not be done.
     *
     */
    public function setPieceOnPosition(Piece $piece, int $y, int $x, bool $force = false, int $cur_y = NULL, int $cur_x = NULL): array
    {

        // If there is nothing on the board for the given coordinates the piece can always be placed there.
        if (is_null($this->board[$y][$x])) {
            $this->board[$y][$x] = $piece;
            return [true, ''];
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

            if ($currentPiece->getName() === 'Flag') {
                return [true, 'Game Over! You hit the flag!'];
            }

            // If there is a piece and the current piece (the one standing there already) can be hit
            // by the piece being moved the piece hit's it and takes it place.
            if ($piece->canHit($currentPiece)) {
                $this->board[$y][$x] = $piece;
                return [true, 'You hit a ' . $currentPiece->getName()];
            } else {
                // The piece lost so gets removed from the board.
                $this->board[$cur_y][$cur_x] = NULL;
                return [true, 'You got hit by ' . $currentPiece->getName()];
            }
        } else {
            // The move is forced so the piece gets always placed.
            $this->board[$y][$x] = $piece;
            return [true, ''];
        }

        return [false, "Unkown error occured"];
    }


    /**
     * Move the piece that is located at (cur_y, cur_x) up with the given distance (default: 1).
     *
     * @param int $cur_y The current y position of the piece that wants to move.
     * @param int $cur_x The current x position of the piece that wants to move.
     * @param int $distance The distance the piece wants to move with (default 1 -- only the scout moves more).
     * @return array Array with as first item a bool to show if the piece can move down with the given distance. The second
     * item in the array is an (error) message for the move.
     */
    public function moveUp(int $cur_y, int $cur_x, int $distance = 1) : array
    {

        $currentPiece = $this->board[$cur_y][$cur_x];
        // If the current piece is null it means that is has not been set yet.
        if (is_null($currentPiece)) {
            return [false, "You cannot move when there is no piece!"];
        }

        // Check if moving up will move out of bounds.
        if ($cur_y - $distance < 0) {
            return [false, "You cannot move outside of the board!"];
        }

        // Check if the current piece can move the given distance (1 by default).
        if(!$currentPiece->canMoveDistance($distance)) {
           return [false, "You cannot move this distance!"];
        }

        // Check if the move is unobstructed (for when scouts move with
        // a larger step then 1)
        if ($distance > 1) {
            $curBoard = $this->getBoard();
            for ($y = $cur_y - $distance + 1; $y < $cur_y; $y++) {
                $piece = $curBoard[$y][$cur_x];
                if (!is_null($piece)) {
                    return [false, "When moving a larger distance (" . $distance . ") you cannot be obstructed!"];
                }
            }
        }

        list($canMove, $msg) = $this->setPieceOnPosition($currentPiece, $cur_y-$distance, $cur_x, false, $cur_y, $cur_x);

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
     * @return array Array with as first item a bool to show if the piece can move down with the given distance. The second
     * item in the array is an (error) message for the move.
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

        // Check if the move is unobstructed (for when scouts move with
        // a larger step then 1)
        if ($distance > 1) {
            $curBoard = $this->getBoard();
            for ($y = $cur_y+1; $y < $cur_y+$distance-1; $y++) {
                $piece = $curBoard[$y][$cur_x];
                if (!is_null($piece)) {
                    return [false, "When moving a larger distance (" . $distance . ") you cannot be obstructed!"];
                }
            }
        }

        list($canMove, $msg) = $this->setPieceOnPosition($currentPiece, $cur_y+$distance, $cur_x, false, $cur_y, $cur_x);
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
     * @return array Array with as first item a bool to show if the piece can move down with the given distance. The second
     * item in the array is an (error) message for the move.
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


        // Check if the move is unobstructed (for when scouts move with
        // a larger step then 1)
        if ($distance > 1) {
            $curBoard = $this->getBoard();
            $row = $curBoard[$cur_y];
            for ($x = $cur_x+1; $x < $cur_x+$distance; $x++) {
                if (!is_null($row[$x])) {
                    return [false, "When moving a larger distance (" . $distance . ") you cannot be obstructed!"];
                }
            }

        }

        list($canMove, $msg) = $this->setPieceOnPosition($currentPiece, $cur_y, $cur_x + $distance, false, $cur_y, $cur_x);
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
     * @return array Array with as first item a bool to show if the piece can move down with the given distance. The second
     * item in the array is an (error) message for the move.
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

        // Check if the move is unobstructed (for when scouts move with
        // a larger step then 1)
        if ($distance > 1) {
            $curBoard = $this->getBoard();
            $row = $curBoard[$cur_y];
            for ($x = $cur_x-1; $x > $cur_x-$distance; $x--) {
                if (!is_null($row[$x])) {
                    return [false, "When moving a larger distance (" . $distance . ") you cannot be obstructed!"];
                }
            }
        }

        list($canMove, $msg) = $this->setPieceOnPosition($currentPiece, $cur_y, $cur_x - $distance, false, $cur_y, $cur_x);
        // If the piece is moved make it's previous position available.
        if ($canMove) {
            $this->board[$cur_y][$cur_x] = NULL;
        }
        return [$canMove, $msg];
    }


    /**
     * Creates a board based on the given json array.
     *
     * This function's main purpose is to be able to load the database (a json file) and be able to regenerate
     * all classes stored as json so that all functionality of these classes can be used (think of moving, etc).
     *
     * @param $json array An array representing the board as stored in the database.
     * @return static A new board instance with all correct positions and pieces;
     */
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
