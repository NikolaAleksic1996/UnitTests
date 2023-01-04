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

//    TDD
// Step 1: Write a test for the feature.
// Step 2: Run your test and watch it fail... since we haven't created that feature yet!
// Step 3: Write as little code as possible to get our test to pass. And
// Step 4: Now that it's passing, refactor your code if needed to make it more awesome
    public function testDinosaurOver10MetersOrGreaterIsLarge(): void
    {
        $dino = new Dinosaur(name: 'Big Eaty', length: 10 );

        self::assertSame(
            'Large',
            $dino->getSizeDescription(),
            'This is supposed to be a Large Dinosaurs'
        ); //that is TDD (step 1 is done)
    }

    public function testDinosaurBetween5And9MetersIsMedium(): void
    {
        $dino = new Dinosaur(name: 'Big Eaty', length: 5 );

        self::assertSame(
            'Medium',
            $dino->getSizeDescription(),
            'This is supposed to be a Medium Dinosaurs'
        );
    }
    public function testDinosaurUnder5MetersIsSmall(): void
    {
        $dino = new Dinosaur(name: 'Big Eaty', length: 4 );

        self::assertSame(
            'Small',
            $dino->getSizeDescription(),
            'This is supposed to be a Small Dinosaurs'
        );
    }
}