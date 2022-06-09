<?php


use PHPUnit\Framework\TestCase;


class MinerTest extends TestCase
{

    function testCanHitBomb() {
        $miner = new \pieces\Miner();
        $bomb = new \pieces\Bomb();

        $canHit = $miner->canHit($bomb);
        $this->assertEquals(true, $canHit);
    }

    function testCannotHitHigherPiece() {
        $miner = new \pieces\Miner();
        $lieutenant = new \pieces\Lieutenant();

        $canHit = $miner->canHit($lieutenant);
        $this->assertEquals(false, $canHit);
    }


}
