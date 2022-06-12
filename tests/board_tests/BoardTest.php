<?php


use PHPUnit\Framework\TestCase;

class BoardTest extends TestCase
{

    public function testCreateBoard()
    {
        // New board is created on construct
        $board = new \board\Board();
        // Test y
        self::assertCount(10, $board->getBoard());

        for ($y = 0; $y < 10; $y++) {
            self::assertCount(10, $board->getBoard()[$y]);
        }

    }

    public function testSetPieceOnPositionOnNull()
    {
        $board = new \board\Board();
        $piece = new \pieces\Flag();

        self::assertEquals(NULL, $board->getPositionOnBoard(0,0));
        $canMove = $board->setPieceOnPosition($piece, 0,0);
        self::assertEquals($piece, $board->getPositionOnBoard(0,0));
        self:self::assertEquals(true, $canMove);
    }

    public function testSetPieceOnHigherPiece()
    {

        $board = new \board\Board();
        $piece1 = new \pieces\Marshal();
        $piece2 = new \pieces\Lieutenant();

        self::assertEquals(NULL, $board->getPositionOnBoard(0,0));
        $canMove = $board->setPieceOnPosition($piece1, 0,0);
        self:self::assertEquals(true, $canMove);
        self::assertEquals($piece1, $board->getPositionOnBoard(0,0));
        $canMove2 =  $board->setPieceOnPosition($piece2, 0,0);
        self::assertEquals(false, $canMove2);
        self::assertEquals($piece1, $board->getPositionOnBoard(0,0));
    }
}
