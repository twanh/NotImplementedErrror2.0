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

    public function getPositionOnBoard(int $y, int $x)
    {
       return $this->board[$y][$x] ;
    }


    /**
     * Creates the 10x10 grid that is used as board;
     * TODO: Add a special value to the water area's! So that it is clear that
     *       there is water there and no one can move there. (e.g: add 0)
     * @return void
     */
    private function createBoard()
    {
        $board = array();
        for ($y = 0; $y < 10; $y++) {
            $row = array();
            for ($x = 0; $x < 10; $x++) {
                $row[$x] = NULL;
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
     * TODO: Make sure that the pieces cannot move in the water regions!!
     *
     * @param Piece $piece The piece that should be assigned to the place.
     * @param int $x The x coordinate of the board.
     * @param int $y The y coordinate of the board.
     * @return bool If the piece could be set there.
     *              This might not be the case if there is a higher piece over there.
     */
    public function setPieceOnPosition(Piece $piece, int $y, int $x): bool
    {
        // Note: Not sure if this is the final/best implementation
        // but it covers setting the pieces in the start and also covers later movement
        // in the game

        if (is_null($this->board[$y][$x])) {
            $this->board[$y][$x] = $piece;
            return true;
        }

        // TODO: When players are implemented this should also check if the currentPiece
        //       and piece belong to the same player, because then they can always move during setup.
        $currentPiece = $this->board[$y][$x];
        if ($piece->canHit($currentPiece)) {
            $this->board[$y][$y] = $piece;
            return true;
        }

        return false;
    }

    /**
     * @param int $cur_y
     * @param int $cur_x
     * @param int $distance
     * @return bool
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
        if ($cur_y - $distance <= 0) {
            // TOP is index 0.
            return false;
        }

        // Check if the current piece can move 1 step.
        if(!$currentPiece->canMoveDistance($distance)) {
           return false;
        }

        return $this->setPieceOnPosition($currentPiece, $cur_y-$distance, $cur_x);

    }


}
