<?php

namespace App\Helpers;

class DurationalOfReading
{
     private $timePerWord = 1;
     private $worldLength;
     private $duration;


      public function setText(string $text)
      {
          $this->worldLength = count(explode(" ",$text));
          $this->duration = $this->worldLength*$this->timePerWord;

          return $this;
      }

      public function getTimePerSecond()
      {
        return $this->duration;
      }

      public function getTimePerMinute()
      {
        return $this->duration/60;
      }
}
