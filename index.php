<?php
  header('Access-Control-Allow-Origin: *');
  header('Access-Control-Allow-Headers: Content-Length');
  header('Access-Control-Expose-Headers: Content-Length');
  header('Timing-Allow-Origin: *');
  
  require_once('./api/deck.php');
  require_once('./api/card.php');
  require_once('./api/hand.php');
  require_once('./api/player.php');
  require_once('./api/evaluateHand.php');
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

  $highestScore = 0;
  
  for ($i = 0; $i < 5; $i++) {
    foreach ($players as $player) {
      $deck->draw(1, $player);
    }
  }

  $json = new stdClass;
  $json->players = array();

  foreach($players as $player) {
    $player->evaluateHand();
    if ($player->getHand()->getScore() > $highestScore) {
      $highestScore = $player->getHand()->getScore();
      $winner = $player;
    }
  }

  foreach($players as $player) {
    $player->winner = ($winner == $player);
  }

  $dados = array(
    'players' => $players
  );
  
  $dados_json = json_encode($dados);

  echo $dados_json;

?>