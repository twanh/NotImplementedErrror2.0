<?php


use PHPUnit\Framework\TestCase;

class SpyTest extends TestCase
{

    public function testCanHitMarshal()
    {

       $spy = new \pieces\Spy('1');
       $marshal = new \pieces\Marshal('2');

       $canHit = $spy->canHit($marshal);
       $this->assertEquals(true, $canHit);

    }

    public function testCannotHitHigherPieces()
    {
        $spy = new \pieces\Spy('1');
        $miner = new \pieces\Miner('2');

        $canHit = $spy->canHit($miner);
        $this->assertEquals(false, $canHit);
    }
}
