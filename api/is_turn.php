<?php

require __DIR__ . '/../classes/Board.php';
require __DIR__ . '/../db/Database.php';

if (isset($_GET['gameid']) && isset($_GET['playerid'])) {

    $gameid = $_GET['gameid'];

    $db = new Database('../data/database.json');
    $game = $db->getGameById($gameid);
    $turn = $db->getTurnForGame($gameid);
    $curPlayerId = $_GET('playerid');

    $player1Id = $game['player1Id'];
    $player2Id = $game['player2Id'];

    if (is_null($turn) or is_null($game)) {
        $data = [
            "message" => "Game with " . $gameid . "does not exist",
            "success" => false,
        ];
        header('Content-Type: application/json');
        echo json_encode($data);
        die();
    }

    if (is_null($player1Id) or is_null($player2Id)) {
        $data = [
            "message" => "Not both players have joined yet!",
            "success" => false,
        ];
        header('Content-Type: application/json');
        echo json_encode($data);
        die();
    }

    $curPlayerNr = 1;
    if ($player2Id == $curPlayerId) {
        $curPlayerNr = 2;
    }

    $isTurn = false;
    if ($turn === $curPlayerNr) {
        $isTurn = true;
    }

    $data = [
        "success" => true,
        "turn" => $isTurn,
    ];

} else {

    $data = [
        "success" => false,
        "message" => "Please provide a gameid and playerid.",
    ];

}
header('Content-Type: application/json');
echo json_encode($data);
die();
