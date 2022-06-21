<?php

require_once __DIR__ . '/../classes/Board.php';
require_once __DIR__ . '/../db/Database.php';

if (isset($_GET['gameid'])) {

    $gameid = $_GET['gameid'];


    $db = new Database('../data/database.json');
    $game = $db->getGameById($gameid);

    if (is_null($game)) {
        $data = [
            "message" => "Game with " . $gameid . "does not exist",
            "success" => false,
        ];
        header('Content-Type: application/json');
        echo json_encode($data);
        die();
    }


    $bothReady = $db->getReadyForGame($gameid);

    $data = [
        "success" => true,
        "ready" => $bothReady,
    ];

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
