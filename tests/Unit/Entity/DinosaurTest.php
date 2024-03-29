<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Dinosaur;
use App\Enum\HealthStatus;
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

    /** @dataProvider sizeDescriptionProvider */
    public function testDinoHasCorrectSizeDescriptionFromLength(int $length, string $expectedSize): void
    {
        $dino = new Dinosaur(name: 'Big Eaty', length: $length );

        self::assertSame(
            $expectedSize,
            $dino->getSizeDescription()
        );
    }

//    public function testDinosaurBetween5And9MetersIsMedium(): void
//    {
//        $dino = new Dinosaur(name: 'Big Eaty', length: 5 );
//
//        self::assertSame(
//            'Medium',
//            $dino->getSizeDescription(),
//            'This is supposed to be a Medium Dinosaurs'
//        );
//    }
//    public function testDinosaurUnder5MetersIsSmall(): void
//    {
//        $dino = new Dinosaur(name: 'Big Eaty', length: 4 );
//
//        self::assertSame(
//            'Small',
//            $dino->getSizeDescription(),
//            'This is supposed to be a Small Dinosaurs'
//        );
//    }

    // Data Providers
    public function sizeDescriptionProvider(): \Generator
    {
        yield '10 Meter Large Din' => [10, 'Large'];
        yield '5 Meter Medium Din' => [5, 'Medium'];
        yield '4 Meter Small Din' => [4, 'Small'];
    }

    public function testIsAcceptingVisitorsByDefault(): void
    {
        $dino = new Dinosaur('Dennis');

        self::assertTrue($dino->isAcceptingVisitors());
    }

    /** @dataProvider healthStatusProvider */
    public function testIsAcceptingVisitorsBasedOnHealthStatus(HealthStatus $healthStatus, bool $expectedVisitorsStatus): void
    {
//        $this->markTestIncomplete();
        $dino = new Dinosaur('Bumpy');
        $dino->setHealth($healthStatus);

        self::assertSame($expectedVisitorsStatus, $dino->isAcceptingVisitors());
    }

    public function healthStatusProvider(): \Generator
    {
        yield 'Sick dino is not accepting visitors' => [HealthStatus::SICK, false];
        yield 'Hungry dino is accepting visitors' => [HealthStatus::HUNGRY, true];
    }
}