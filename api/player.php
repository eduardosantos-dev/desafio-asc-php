<?php
  class Player implements JsonSerializable{

    private $name;
    private $hand;
    private $evaluate;

    public function __construct($id, $name) {
      $this->playerID = $id;
      $this->name = $name;
      $this->hand = new Hand();
      $this->evaluate = new evaluateHand();
    }

    public function __toString() {
      return json_encode($this->jsonSerialize(), JSON_UNESCAPED_SLASHES);
    }
    
    public function jsonSerialize() {
      return get_object_vars($this);
    }

    public function getName() {
      return $this->name;
    }

    public function getPlayerID() {
      return $this->playerID;
    }

    public function getHand() {
      return $this->hand;
    }

    public function getEvaluate() {
      return $this->evaluate;
    }

    public function evaluateHand() {
      $this->evaluate->evaluate($this->getHand());
      
      // Soma o rank da mão com o score da carta mais alta.
      // Como critério de desempate, utilizo a carta mais alta da mão. (eu inventei essa regra ¯\_(ツ)_/¯)
      $this->getHand()->setScore($this->evaluate->getHandRank() 
                                  + $this->evaluate->getHighestCardScore()/100);
    }
  }
?>