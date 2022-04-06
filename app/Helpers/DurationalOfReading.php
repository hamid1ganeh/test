<?php

namespace App\Helpers;

class DurationalOfReading
{
     private $timePerWord = 1;
     private $worldLength;
     private $duration;


      public function __construct(string $text)
      {
          $this->worldLength = count(explode(" ",$text));
          $this->duration = $this->worldLength*$this->timePerWord;
      }

      public function getTimePerSecond()
      {
        return $this->duration;
      }

      public function getTimePerMinite()
      {
        return $this->duration/60;
      }
}
