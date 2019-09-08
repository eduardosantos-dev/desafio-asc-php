<?php
  require_once('./phpconnect.php');

  $stmt = $conn->query("SELECT * FROM poker_match_history " . 
                        "INNER JOIN poker_player " . 
                        " ON poker_player.playerID = poker_match_history.playerID " .
                        "ORDER BY winnerScore DESC " .
                        "LIMIT 10");
  while ($row = $stmt->fetch()) {
    $match = new StdClass();
    $match->playerName = $row['name'];
    $match->score = $row['winnerScore']; 
    $match->handName = $row['handName'];
    $match->matchDate = $row['matchDate'];

    $ranking[] = $match;
  }

  $dados = array(
    'ranking' => $ranking
  );

  echo json_encode($dados);
?>