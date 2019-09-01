<?php
  require_once('deck.php');
  require_once('card.php');
  require_once('hand.php');
  require_once('player.php');
  require_once('evaluateHand.php');
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
  $deck = new Deck();
  $deck->createDeck();
  $deck->shuffle();

  $players = array();
  $players[] = new Player('Jogador 1');
  $players[] = new Player('Jogador 2');
  $players[] = new Player('Jogador 3');
  $players[] = new Player('Jogador 4');

  $highestScore = 0;

  for ($i = 0; $i < 5; $i++) {
    foreach ($players as $player) {
      $deck->draw(1, $player);
    }
  }

  foreach($players as $player) {
    echo '<b>'.$player->getName().'</b><br><br>';
    $player->evaluateHand();

    foreach($player->getHand()->getCards() as $card) {
      echo '<br>' . $card->getSuit().' '.$card->getValue();
    }
    echo '<br>SCORE: '.$player->getHand()->getScore();

    if ($player->getHand()->getScore() > $highestScore) {
      $highestScore = $player->getHand()->getScore();
      $winner = $player;
    }
    echo '<br><br><br><br>';
  }

  echo 'Vencedor: ' . $winner->getName();

?>