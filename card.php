<?php
  class Card implements JsonSerializable{
    private $suit;
    private $value;
    private $rank;

    public function __construct($suit, $value, $rank) {
      $this->suit = $suit;
      $this->value = $value;
      $this->rank = $rank;
    }

    public function __toString() {
      return json_encode($this->jsonSerialize(), JSON_UNESCAPED_SLASHES);
    }
    
    public function jsonSerialize() {
      return get_object_vars($this);
    }
    
    public function getSuit() {
      return $this->suit;
    }

    public function getValue() {
      return $this->value;
    }

    public function getRank() {
      return $this->rank;
    }   

    public function setRank($rank) {
      $this->rank = $rank;
    }
    
  }
?>