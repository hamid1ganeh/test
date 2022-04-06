<?php

namespace Tests\Unit;
use App\Helpers\DurationalOfReading;

use PHPUnit\Framework\TestCase;

class DurationOfReadingTest extends TestCase
{
    public function testCanGetDurationOfReadingText()
    {
        $text = 'This is for test';

        $dor = new DurationalOfReading($text);

        $this->assertTrue(true);

        $this->assertEquals(4,$dor->getTimePerSecond());
        $this->assertEquals(4/60,$dor->getTimePerMinite());
    }
}
