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
}
