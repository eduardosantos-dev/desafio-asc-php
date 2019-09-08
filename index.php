<?php
  
  require_once('./api/deck.php');
  require_once('./api/card.php');
  require_once('./api/hand.php');
  require_once('./api/player.php');
  require_once('./api/evaluateHand.php');
  require_once('./api/phpconnect.php');

  $deck = new Deck();
  $deck->createDeck();
  $deck->shuffle();

  $players = array();

  $stmt = $conn->query("SELECT * FROM poker_player");
  while ($row = $stmt->fetch()) {
    $players[] = new Player($row['playerID'], $row['name']);
  }

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

    if ($player->winner) {
      $sql = "INSERT INTO poker_match_history(playerID, winnerScore, handName) VALUES (?,?,?)";
      $stmt = $conn->prepare($sql);
  
      $id = $player->getPlayerID();
      $score = $player->getHand()->getScore();
      $hand_name = $player->getEvaluate()->getHandName();
  
      try {
        $stmt->execute([$id, $score, $hand_name]);
        $conn->beginTransaction();
        $conn->commit();
      } catch (Exception $e) {
        $conn->rollback();
        throw $e;
      }
    }
  }

  $dados = array(
    'players' => $players
  );
  
  $dados_json = json_encode($dados);

  echo $dados_json;


?>