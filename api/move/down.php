<?php

require __DIR__ . '/../classes/Board.php';
require __DIR__ . '/../db/Database.php';

if (isset($_POST['gameid']) && isset($_POST['player1Id']) && isset($_POST['player2Id'])) {

    $gameid = $_POST['gameid'];

    $db = new Database('../data/database.json');
    $game = $db->getGameById($gameid);
    $board = 

    $player1Id = $_POST['player1Id'];
    $player2Id = $_POST['player2Id'];
    $playersTurn = 0; // How do we get this data? change db.json? or API?
    $cur_x = $_POST['cur_x'];
    $cur_y = $_POST['cur_y'];

    //check errors
    if (is_null($game)) {
        $data = [
            "message" => "Game with " . $gameid . "does not exist",
            "success" => false,
        ];
        header('Content-Type: application/json');
        echo json_encode($data);
        die();
    }

    if (is_null($player1Id)) {
        $data = [
            "message" => "Player with " . $player1Id . "does not exist",
            "success" => false,
        ];
        header('Content-Type: application/json');
        echo json_encode($data);
        die();
    }
    if (is_null($player2Id)) {
        $data = [
            "message" => "Player with " . $player2Id . "does not exist",
            "success" => false,
        ];
        header('Content-Type: application/json');
        echo json_encode($data);
        die();
    }

    //check if right player made turn

    if ($playersTurn != $somehting) {
        $data = [
            "message" => "It is not your turn!",
            "success" => false,
        ];
        header('Content-Type: application/json');
        echo json_encode($data);
        die();
    }

    //Make move up
    
    $board->moveDown($cur_x, $cur_y);
    $data = [
        "message" => "Nice move!",
        "success" => True,
    ];

    //return data
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