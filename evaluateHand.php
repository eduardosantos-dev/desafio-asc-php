<?php

  class evaluateHand {

    private $high_card;
    private $hand_name;
    private $matches;
    private $hand_rank;

    public function evaluate($hand) {
      $this->highCard($hand);
      $this->handPairs($hand);
      $this->threeOfAKind($hand);
      $this->straight($hand);
      $this->flush($hand);
      $this->fullHouse($hand);
      $this->fourOfAKind($hand);
      $this->straightFlush($hand);
      $this->royalFlush($hand);
    }

    public function highCard($hand) {
      // returns the hands high card
      $high = max($hand->getRanks());
      foreach ($hand->getCards() as $card) {
        if ($card->getRank() == $high) {
          $this->high_card = $card;
        }
      }
      echo "High Card: " . $this->high_card->getValue();
      $this->hand_rank = 1;
    }

    public function handPairs($hand) {
      //returns pair (one and two pair) score (if any)
      $this->matches = array();

      foreach(array_count_values($hand->getRanks()) as $value => $count) {
        if ($count == 2) {
          if (count($this->matches) < 2) {
            $this->matches[] = $value;
          }
        }
      }

      if (count($this->matches) == 1) {
        // one pair
        foreach ($this->matches as $match) {
          foreach ($hand->getCards() as $card) {
            if($match == $card->getRank()) {
              // found the matching card, set the hand name.
              $this->hand_name = "Pair of " . $card->getValue() . "'s";
              $this->hand_rank = 2;
            }
          }
        }
      }

      if (count($this->matches) == 2) {
        // two pair
        $this->hand_name = "Two pair: ";
        $this->hand_rank = 3;
        foreach ($this->matches as $match) {
          foreach ($hand->getCards() as $card) {
            if ($match == $card->getRank()) {
              // found a matching card
              $values[] = $card->getValue();
            }
          }
        }

        // get rid of duplicates
        $values = array_unique($values);
        $i = 0;
        foreach ($values as $value) {
          if ($i == 0) {
            $this->hand_name .= $value . "'s ";
          } else {
            $this->hand_name .= "and " . $value . "'s"; 
          }
          $i++;
        }
      }
      $array = $this->matches;
      return $array;
    }

    public function threeOfAKind($hand) {
      // returns tofak score (if any)

      $this->matches = array();

      foreach (array_count_values($hand->getRanks()) as $value => $count) {
        if ($count === 3) {
          // if there are three of the same card, add to array
          if (count($this->matches) < 1) {
            $this->matches[] = $value;
          }
        }
      }

      if (count($this->matches) == 1) {
        // three of a kind
        foreach ($this->matches as $match) {
          foreach ($hand->getCards() as $card) {
            if ($match == $card->getRank()) {
              // found the matching card, set the hand name
              $this->hand_name = "Three of a kind: " . $card->getValue() . "'s";
              $this->hand_rank = 4;
            }
          }
        }
      }

      $array = $this->matches;
      return $array;
    }

    public function straight($hand) {
      // returns straight score (if any)

      // checks for an A,2,3,4,5 straight
      // This has to be done because by default A's rank is 13

      if (array_search(13, $hand->getRanks()) !== FALSE
           && array_search(1, $hand->getRanks()) !== FALSE 
           && array_search(2, $hand->getRanks()) !== FALSE 
           && array_search(3, $hand->getRanks()) !== FALSE 
           && array_search(4, $hand->getRanks()) !== FALSE) {
        foreach ($hand->getCards() as $card) {
            if ($card->getRank() == 13) {
                $card->setRank(0);
            }
        }
      } 

      $min = min($hand->getRanks());
      $max = max($hand->getRanks());

      $i = 0;
      do {
        // couldn't find the current value in ranks, break out of loop
        if (array_search($min, $hand->getRanks()) === FALSE) {
          break;
        }

        if ($i >= 4) {
          // looped five times successfully straight.
          $this->hand_name = "Straight to " . $this->high_card->getValue();
          $this->hand_rank = 5;
          return true;
        }
        $min ++;
        $i++;
      } while ($min <= $max);
    }

    public function flush($hand) {
      // returns flush score (if any)
      $this -> matches = array();

      foreach(array_count_values($hand->getSuits()) as $value => $count) {
        if ($count == 5) {
          // someone has a flush
          if (count($this->matches) < 1) {
            $this->matches[] = $value;
          }
        }
      }

      if (count($this->matches) == 1) {
        // flush
        $this->hand_name = "Flush: " . $this->high_card->getValue() . " high";
        $this->hand_rank = 6;
        return true;
      }
    }

    public function fullHouse($hand) {
      // returns full house result score (if any)

      $this->matches = array();

      if (count($this->handPairs($hand)) == 1 
          && count($this->threeOfAKind($hand)) > 0){
            $i = 0;

            foreach ($this->handPairs($hand) as $card) {
              $pair = $card;
            }

            foreach ($this->threeOfAKind($hand) as $card) {
              $three = $card;
            }

            foreach ($hand->getCards() as $card) {
              if ($pair == $card->getRank()) {
                $values['pair'] = $card->getValue();
              }
            }

            foreach ($hand->getCards() as $card) {
              if ($three == $card->getRank()) {
                $values['threeofakind'] = $card->getValue();
              }
            }

            $values = array_unique($values);

            $this->hand_name = "Full house: " . $values['threeofakind'] 
            . "'s full of " . $values['pair'] . "'s";
            $this->hand_rank = 7;
          }
    }

    public function fourOfAKind($hand) {
      // returns fOAK score (if any)

      $this->matches = array();

      foreach (array_count_values($hand->getRanks()) as $value => $count) {
        if ($count === 4) {
          // if there are four of the same card, add to array
          if (count($this->matches) < 1) {
            $this->matches[] = $value;
          }
        }
      }

      if (count($this->matches) == 1) {
        // four of a kind
        foreach ($this->matches as $match) {
          foreach ($hand->getCards() as $card) {
            if ($match == $card->getRank()) {
              // found the matching card, set the hand name
              $this->hand_name = "Four of a kind: " . $card->getValue() . "'s";
              $this->hand_rank = 8;
            }
          }
        }
      }

      $array = $this->matches;
      return $array;
    }

    public function straightFlush($hand) {
      // returns a straight flush score (if any)
      if ($this->straight($hand) && $this->flush($hand)) {
        $this->hand_name = "Straight flush: " . $this->high_card->getValue() . " high";
        $this->hand_rank = 9;
      }
    }

    public function royalFlush($hand) {
      // returns a royal flush score (if any)

      foreach(array_count_values($hand->getSuits()) as $value => $count) {
        if ($count == 5) { // All cards have the same suit
          $min = 9;
          $max = 13;
          $i = 0;

          do {
              //couldn't find the current value in ranks, break out of loop.
              if (array_search($min, $hand->getRanks()) === FALSE) {
                break;
              } 

              if ($i >= 4) {
                //looped five times successfully, straight.
                $this->hand_name = "Royal Flush!";
                $this->hand_rank = 10;
                return true;
              }
              $min++;
              $i++;
          } while ($min <= $max);
        }
      }
    }

    public function getHandName() {
      if (!empty($this->hand_name)) {
          return $this->hand_name;
      }
    }

    public function getHandRank() {
      if (!empty($this->hand_rank)) {
        return $this->hand_rank;
      }
    }
    
    public function getHighestCardScore() {
      if (!empty($this->high_card)) {
        return $this->high_card->getRank();
      }
    }
  }
?>