<?php

namespace board;

use pieces\Piece;

class Board
{
    protected $board;

    public function getBoard()
    {
        return $this->board;
    }

    public function getBoardForBlue()
    {
        $current = $this->board;
        $reverse = array();
        for ($y = 9; $y >= 0; $y--)
        {
            $row = array();
            for ($x = 9; $x >= 0; $x--)
                $row[] = $current[$y][$x];
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
     * TODO: Add a force parameter (or something like it) to make sure that the hit logic
     *       does not get in the way when doing the pieces setup.
     *
     * @param Piece $piece The piece that should be assigned to the place.
     * @param int $x The x coordinate of the board.
     * @param int $y The y coordinate of the board.
     * @return bool If the piece could be set there.
     *              This might not be the case if there is a higher piece over there.
     */
    public function setPieceOnPosition(Piece $piece, int $y, int $x, bool $force = false): bool
    {
        // Note: Not sure if this is the final/best implementation
        // but it covers setting the pieces in the start and also covers later movement
        // in the game

        if (is_null($this->board[$y][$x])) {
            $this->board[$y][$x] = $piece;
            return true;
        }

        // Make sure that players cannot place pieces on water
        if ($this->board[$y][$x] === 'WATER') {
            return false;
        }

        // TODO: When players are implemented this should also check if the currentPiece
        //       and piece belong to the same player, because then they can always move during setup.
        $currentPiece = $this->board[$y][$x];
        if (!$force){
            if ($piece->canHit($currentPiece)) {
                $this->board[$y][$y] = $piece;
                return true;
            }
        } else {
            $this->board[$y][$y] = $piece;
            return true;
        }

        return false;
    }


    /**
     * Move the piece that is located at (cur_x, cur_x) up with the given distance (default: 1).
     *
     * Note: for blue, the board is shown upside down, so this would actually move it down for them (?).
     *
     * @param int $cur_y The current y position of the piece that wants to move.
     * @param int $cur_x The current x position of the piece that wants to move.
     * @param int $distance The distance the piece wants to move with (default 1 -- only the scout moves more).
     * @return bool If the piece can move up with the given distance.
     */
    public function moveUp(int $cur_y, int $cur_x, int $distance = 1) : bool
    {
        // TODO: When players are added check if the current piece belongs to the
        //       correct player.

        $currentPiece = $this->board[$cur_y][$cur_x];
        // If the current piece is null it means that is has not been set yet.
        if (is_null($currentPiece)) {
            // TODO: Perhaps thrown an error?? Since NULL cannot move?
            return false;
        }

        // Test if moving up will move out of bounds.
        if ($cur_y - $distance < 0) {
            // TOP is index 0.
            return false;
        }

        // Check if the current piece can move the given distance (1 by default).
        if(!$currentPiece->canMoveDistance($distance)) {
           return false;
        }

        return $this->setPieceOnPosition($currentPiece, $cur_y-$distance, $cur_x);

    }

    /**
     * Move the piece that is located at (cur_x, cur_x) down with the given distance (default: 1).
     *
     * Note: for blue, the board is shown upside down, so this would actually move it up for them (?).
     *
     * @param int $cur_y The current y position of the piece that wants to move.
     * @param int $cur_x The current x position of the piece that wants to move.
     * @param int $distance The distance the piece wants to move with (default 1 -- only the scout moves more).
     * @return bool If the piece can move down with the given distance.
     */
    public function moveDown(int $cur_y, int $cur_x, int $distance = 1) : bool
    {
        $currentPiece = $this->board[$cur_y][$cur_x];

        if (is_null($currentPiece)) {
            return false;
        }

        // Check if there is room at the bottom to move to (not out of bounds)
        if ($cur_y + $distance >= 10) {
            return false;
        }

        if (!$currentPiece->canMoveDistance($distance)) {
            return false;
        }

        return $this->setPieceOnPosition($currentPiece, $cur_y+$distance, $cur_x);
    }

    /**
     * Move the piece that is located at (cur_x, cur_x) right with the given distance (default: 1).
     *
     * @param int $cur_y The current y position of the piece that wants to move.
     * @param int $cur_x The current x position of the piece that wants to move.
     * @param int $distance The distance the piece wants to move with (default 1 -- only the scout moves more).
     * @return bool If the piece can move right with the given distance.
     */
    public function moveRight(int $cur_y, int $cur_x, int $distance = 1) : bool
    {

        $currentPiece = $this->board[$cur_y][$cur_x];

         if (is_null($currentPiece)) {
            return false;
         }

         // Check if there is room to move to the right
        if ($cur_x + $distance >= 10) {
            return false;
        }

        if (!$currentPiece->canMoveDistance($distance)) {
            return false;
        }


         return $this->setPieceOnPosition($currentPiece, $cur_y, $cur_x + $distance);
    }

    /**
     * Move the piece that is located at (cur_x, cur_x) left with the given distance (default: 1).
     *
     * @param int $cur_y The current y position of the piece that wants to move.
     * @param int $cur_x The current x position of the piece that wants to move.
     * @param int $distance The distance the piece wants to move with (default 1 -- only the scout moves more).
     * @return bool If the piece can move left with the given distance.
     */
    public function moveLeft(int $cur_y, int $cur_x, int $distance = 1) : bool
    {

        $currentPiece = $this->board[$cur_y][$cur_x];

        if (is_null($currentPiece)) {
            return false;
        }

        // Check if there is room to move to the right
        if ($cur_x - $distance < 0) {
            return false;
        }

        if (!$currentPiece->canMoveDistance($distance)) {
            return false;
        }

        return $this->setPieceOnPosition($currentPiece, $cur_y, $cur_x + $distance);
    }


    public static function fromJson($json) : Board
    {
        $board = new static();

        for ($y =0; $y < 10; $y++) {
            for ($x = 0; $x < 10; $x++) {
                if (!is_null($json[$y][$x]) && $json[$y][$x] !== 'WATER') {
                    $piece = Piece::fromJson($json[$y][$x]);
                    $board->setPieceOnPosition($piece, $y, $x, true);
                }
            }
        }

        return $board;

    }



}
