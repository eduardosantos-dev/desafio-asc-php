<?php
  class Player {

    private $name;
    private $hand;
    private $evaluate;

    public function __construct($name) {
      $this->name = $name;
      $this->hand = new Hand();
      $this->evaluate = new evaluateHand();
    }

    public function getName() {
      return $this->name;
    }

    public function getHand() {
      return $this->hand;
    }

    public function evaluateHand() {
      $this->evaluate->evaluate($this->getHand());
      echo ", ".$this->evaluate->getHandName();
      
      $this->getHand()->setScore($this->evaluate->getHandRank() 
                                  + $this->evaluate->getHighestCardScore()/100);
    }
  }
?>