<?php


use PHPUnit\Framework\TestCase;


class MinerTest extends TestCase
{

    function testCanHitBomb() {
        $miner = new \pieces\Miner('1');
        $bomb = new \pieces\Bomb('2');

        $canHit = $miner->canHit($bomb);
        $this->assertEquals(true, $canHit);
    }

    function testCannotHitHigherPiece() {
        $miner = new \pieces\Miner('1');
        $lieutenant = new \pieces\Lieutenant('2');

        $canHit = $miner->canHit($lieutenant);
        $this->assertEquals(false, $canHit);
    }


}
