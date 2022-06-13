<?php

use PHPUnit\Framework\TestCase;

class PieceTest extends TestCase
{

    public function testCanHitHigherNrWins()
    {
        // Test that higher number wins
        $p1 = new \pieces\Piece('1');
        $p1->value = '10'; // Highest value;
        $p2 = new \pieces\Piece('2');
        $p2->value = '9';

        self::assertEquals(true, $p1->canHit($p2));
        self::assertEquals(false, $p2->canHit($p1));

    }

    public function testCanHitSelf()
    {
        // TODO: Make this test again -- this will not work anymore since
        //      the owner id is the same.
        $p1 = new \pieces\Piece('1');
        $p1->value = '10'; // Highest value;
        self::assertEquals(true, $p1->canHit($p1));
    }

    public function testNumericLosesFromBomb()
    {
        // Note: miner wins from bomb, but this is tested in the specific miner tests.
        $p1 = new \pieces\Piece('1');
        $p1->value = '9';
        $p2 = new \pieces\Piece('2');
        $p2->value = 'B';

        self::assertEquals(false, $p1->canHit($p2));
    }

    public function testCanMove()
    {
        // TODO: Add tests once implemented
    }

    // TODO: Add test to check that pieces belonging to the same player cannot hit each other.

}
