<?php


use PHPUnit\Framework\TestCase;

class SpyTest extends TestCase
{

    public function testCanHitMarshal()
    {

       $spy = new \pieces\Spy();
       $marshal = new \pieces\Marshal();

       $canHit = $spy->canHit($marshal);
       $this->assertEquals(true, $canHit);

    }

    public function testCannotHitHigherPieces()
    {
        $spy = new \pieces\Spy();
        $miner = new \pieces\Miner();

        $canHit = $spy->canHit($miner);
        $this->assertEquals(false, $canHit);
    }
}
