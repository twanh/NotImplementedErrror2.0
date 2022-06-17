<?php

require __DIR__ . '/../classes/Board.php';
require __DIR__ . '/../db/Database.php';

if (isset($_GET['gameid'])) {

    $gameid = $_GET['gameid'];

    $player1Joined = false;
    $player2Joined = false;

    $db = new Database('../data/database.json');
    $game = $db->getGameById($gameid);
    $board = $db->getBoard($gameid);
    if (is_null($game)) {
        $data = [
            "message" => "Game with " . $gameid . "does not exist",
            "success" => false,
        ];
        header('Content-Type: application/json');
        echo json_encode($data);
        die();
    }

    if(!is_null($game['player1Id'])) {
        $player1Joined = true;
    }

    if(!is_null($game['player2Id'])) {
        $player2Joined = true;
    }

    // TESTING
    // TODO: REmOVE

    $p1 = new \pieces\Major($game['player1Id']);
    $board->setPieceOnPosition($p1, 0, 0);
    $p2 = new \pieces\Major($game['player2Id']);
    $board->setPieceOnPosition($p2, 0, 0);

    $db->updateGame($gameid, NULL, NULL, $board);

    $data = [
        "success" => false,
        "message" => "Not all players have joined!",
    ];
    if ($player1Joined && $player2Joined) {
        $data = [
            "success" => true,
            "message" => "All players have joined!",
        ];
    }

    header('Content-Type: application/json');
    echo json_encode($data);
    die();

} else {

    $data = [
        "success" => false,
        "message" => "Please provide a gameid",
    ];

    header('Content-Type: application/json');
    echo json_encode($data);
    die();
}