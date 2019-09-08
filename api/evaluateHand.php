<?php

/*
  Royal Flush: Sequência de mesmo naipe de 10 a As
  Straight Flush: Sequência de mesmo naipe fora do intervalo de 10 a As
  Quadra: Quatro cartas iguais
  Full House: Duas cartas iguais junto com três cartas iguais
  Flush: Cinco cartas diferentes do mesmo naipe
  Sequência: Cinco cartas em sequência de naipes diferentes
  Trinca: Apenas três cartas iguais
  Dois pares: Apenas dois pares de cartas iguais
  Um par: Apenas um par de cartas iguais
  Maior carta: Valor da maior carta.

*/

  class evaluateHand implements JsonSerializable{

    private $high_card;
    private $hand_name;
    private $matches;
    private $hand_rank;

    public function __toString() {
      return json_encode($this->jsonSerialize(), JSON_UNESCAPED_SLASHES);
    }
    
    public function jsonSerialize() {
      return get_object_vars($this);
    }

    public function evaluate($hand) {
      $this->maiorCarta($hand);
      $this->pares($hand);
      $this->trinca($hand);
      $this->straight($hand);
      $this->flush($hand);
      $this->fullHouse($hand);
      $this->quadra($hand);
      $this->straightFlush($hand);
      $this->royalFlush($hand);
    }

    public function maiorCarta($hand) {
      // retorna a carta mais alta da mão
      $high = max($hand->getRanks());
      foreach ($hand->getCards() as $card) {
        if ($card->getRank() == $high) {
          $this->high_card = $card;
          $this->hand_name = "Carta mais alta: " . $card->getValue();
        }
      }
      $this->hand_rank = 1;
    }

    public function pares($hand) {
      // retorna um ou dois pares, se houver
      $this->matches = array();

      foreach(array_count_values($hand->getRanks()) as $value => $count) {
        if ($count == 2) {
          if (count($this->matches) < 2) {
            $this->matches[] = $value;
          }
        }
      }

      if (count($this->matches) == 1) {
        // Um par
        foreach ($this->matches as $match) {
          foreach ($hand->getCards() as $card) {
            if($match == $card->getRank()) {
              $this->hand_name = "Par de " . $card->getValue() . "'s";
              $this->hand_rank = 2;
            }
          }
        }
      }

      if (count($this->matches) == 2) {
        // Dois pares
        $this->hand_name = "Dois pares: ";
        $this->hand_rank = 3;
        foreach ($this->matches as $match) {
          foreach ($hand->getCards() as $card) {
            if ($match == $card->getRank()) {
              $values[] = $card->getValue();
            }
          }
        }

        $values = array_unique($values);
        $i = 0;
        foreach ($values as $value) {
          if ($i == 0) {
            $this->hand_name .= $value . "'s ";
          } else {
            $this->hand_name .= "e " . $value . "'s"; 
          }
          $i++;
        }
      }
      $array = $this->matches;
      return $array;
    }

    public function trinca($hand) {
      // retorna trinca, se houver

      $this->matches = array();

      foreach (array_count_values($hand->getRanks()) as $value => $count) {
        if ($count === 3) {
          // Se existem 3 cartas iguais, adiciono ao array
          if (count($this->matches) < 1) {
            $this->matches[] = $value;
          }
        }
      }

      if (count($this->matches) == 1) {
        // Trinca
        foreach ($this->matches as $match) {
          foreach ($hand->getCards() as $card) {
            if ($match == $card->getRank()) {
              $this->hand_name = "Trinca: " . $card->getValue() . "'s";
              $this->hand_rank = 4;
            }
          }
        }
      }

      $array = $this->matches;
      return $array;
    }

    public function straight($hand) {
      // retorno um straight(sequência), se houver

      // verifica a sequência A,2,3,4,5
      // É necessário validar essa sequência pois o valor padrão do A é 13

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

      $i = 1;
      do {
        // Se não encontrar o valor atual nos ranks, saio do loop
        if (array_search($min, $hand->getRanks()) === FALSE) {
          break;
        }

        if ($i >= 5) {
          // passou pelo loop 5 vezes seguidas.
          $this->hand_name = "Sequência até " . $this->high_card->getValue();
          $this->hand_rank = 5;
          return true;
        }
        $min ++;
        $i++;
      } while ($min <= $max);
    }

    public function flush($hand) {
      // retorna flush, se houver
      $this -> matches = array();

      foreach(array_count_values($hand->getSuits()) as $value => $count) {
        if ($count == 5) {
          // 5 cartas do mesmo naipe
          if (count($this->matches) == 0) {
            $this->matches[] = $value;
          }
        }
      }

      if (count($this->matches) > 0) {
        // flush
        $this->hand_name = "Flush: " . $this->high_card->getValue() . " alta";
        $this->hand_rank = 6;
        return true;
      }
    }

    public function fullHouse($hand) {
      // retorna um full house, se houver

      $this->matches = array();

      if (count($this->pares($hand)) == 1 
          && count($this->trinca($hand)) > 0){
            $i = 0;

            foreach ($this->pares($hand) as $card) {
              $pair = $card;
            }

            foreach ($this->trinca($hand) as $card) {
              $three = $card;
            }

            foreach ($hand->getCards() as $card) {
              if ($pair == $card->getRank()) {
                $values['par'] = $card->getValue();
              }
            }

            foreach ($hand->getCards() as $card) {
              if ($three == $card->getRank()) {
                $values['trinca'] = $card->getValue();
              }
            }

            $values = array_unique($values);

            $this->hand_name = "Full house: " . $values['trinca'] 
            . "'s e " . $values['par'] . "'s";
            $this->hand_rank = 7;
          }
    }

    public function quadra($hand) {
      // retorna quadra, se houver

      $this->matches = array();

      foreach (array_count_values($hand->getRanks()) as $value => $count) {
        if ($count === 4) {
          // se houverem quatro cartas iguais, adiciono ao array
          if (count($this->matches) < 1) {
            $this->matches[] = $value;
          }
        }
      }

      if (count($this->matches) == 1) {
        // quadra
        foreach ($this->matches as $match) {
          foreach ($hand->getCards() as $card) {
            if ($match == $card->getRank()) {
              $this->hand_name = "Quadra: " . $card->getValue() . "'s";
              $this->hand_rank = 8;
            }
          }
        }
      }

      $array = $this->matches;
      return $array;
    }

    public function straightFlush($hand) {
      // retorna um straight flush, se houver
      if ($this->straight($hand) && $this->flush($hand)) {
        $this->hand_name = "Straight flush até " . $this->high_card->getValue();
        $this->hand_rank = 9;
      }
    }

    public function royalFlush($hand) {
      // retorna um royal flush, se houver.

      foreach(array_count_values($hand->getSuits()) as $value => $count) {
        if ($count == 5) { // Todas as cartas têm o mesmo naipe
          $min = 9;
          $max = 13;
          $i = 0;

          do {
              // Se não encontrar o valor atual nos ranks, saio do loop
              if (array_search($min, $hand->getRanks()) === FALSE) {
                break;
              } 

              if ($i >= 4) {
                // Passou pelo loop cinco vezes seguidas
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