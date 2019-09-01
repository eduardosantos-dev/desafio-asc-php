<?php
  class Hand extends Card implements JsonSerializable {
    private $cards;
    private $score;

    public function __construct() {
      $this->clearHand();
    }

    public function __toString() {
      return json_encode($this->jsonSerialize(), JSON_UNESCAPED_SLASHES);
    }
    
    public function jsonSerialize() {
      return get_object_vars($this);
    }

    public function clearHand() {
      $this->cards = array();
    }

    public function addCardToHand($card) {
      // $card is a Card object
      $this->cards[] = $card;
    }
    
    public function getCards() {
      return $this->cards;
    }

    public function getSuits() {
      $array = array();
      foreach($this->getCards() as $card) {
        $array[] = $card->getSuit();
      }

      return $array;
    }

    public function getRanks() {
      $array = array();
      foreach ($this->getCards() as $card) {
        $array[] = $card->getRank();
      }
      return $array;
    }

    public function setScore($score) {
      $this->score = $score;
    }
    
    public function getScore() {
        return $this->score;
    }
  }
?>