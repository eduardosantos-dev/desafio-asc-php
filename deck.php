<?php 
  class Deck implements JsonSerializable{
    private $deck = array();

    public function __construct() {
      $this->deck = $this->createDeck();
    }

    public function createSuit($suit) {
      return array(
        new Card($suit, '2', 1),
        new Card($suit, '3', 2),
        new Card($suit, '4', 3),
        new Card($suit, '5', 4),
        new Card($suit, '6', 5),
        new Card($suit, '7', 6),
        new Card($suit, '8', 7),
        new Card($suit, '9', 8),
        new Card($suit, '10', 9),
        new Card($suit, 'J', 10),
        new Card($suit, 'Q', 11),
        new Card($suit, 'K', 12),
        new Card($suit, 'A', 13)
      );
    }

    public function createDeck() {
      return array_merge(
        $this->createSuit('S'), // Spades
        $this->createSuit('C'), // Clubs
        $this->createSuit('H'), // Hearts
        $this->createSuit('D')  // Diamonds
      );
    }

    public function shuffle() {
      return shuffle($this->deck);
    }

    public function __toString() {
      return json_encode($this->jsonSerialize(), JSON_UNESCAPED_SLASHES);
    }

    public function jsonSerialize() {
      $array = $this->deck;
      foreach ($array as &$card) {
          $card = $card->jsonSerialize();
      }
      return $array;
    }

    public function draw($ammount, $player) {
      $hand = $player->getHand();
      for ($i = 0; $i < $ammount; $i++) {
        $card = array_pop($this->deck);
        $hand->addCardToHand($card);
      }
    }
  }
?>