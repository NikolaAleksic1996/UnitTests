<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Dinosaur;
use PHPUnit\Framework\TestCase;

class DinosaurTest extends TestCase
{

//    public function testItWorks(): void
//    {
//        self::assertEquals(42, 42);
//    }
//
//    public function testItWorksTheSame(): void
//    {
//        self::assertSame(42, 42);
//    }


    public function testCanGetAndSetData(): void
    {
        $dino = new Dinosaur(
            name: 'Big Eaty',
            genus: 'Tyrannosaurus',
            length: 15,
            enclosure: 'Paddock A'
        );

//        self::assertGreaterThan(
//            $dino->getLength(),
//            10,
//            'Dino is supposed to be bigger than 10 meters.'
//        );

        self::assertSame('Big Eaty', $dino->getName());
        self::assertSame('Tyrannosaurus', $dino->getGenus());
        self::assertSame(15, $dino->getLength());
        self::assertSame('Paddock A', $dino->getEnclosure());
    }
}