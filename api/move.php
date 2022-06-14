<?php

require __DIR__ . '/../classes/Board.php';
require __DIR__ . '/../db/Database.php';

if (isset($_POST['gameid']) && isset($_POST['player1Id']) && isset($_POST['player2Id'])) {

    $gameid = $_POST['gameid'];
    $player1Id = $_POST['player1Id'];
    $player2Id = $_POST['player2Id'];
    $player1Id_turn = true; // How do we get this data? change db.json?
    $move_from = $_POST['move_from'];
    $move_to = $_POST['move_to'];
    $piece_moved = ""; // Somehow calculate which Piece has moved in php (NOT FRONT-END!!!)

    $data = [
        "message" => "Game with " . $gameid . "does not exist",
        "success" => false,
    ];
    
    //check errors
    if (is_null($game)) {
        echo json_encode($data);
        die();
    }
    if (is_null($player1Id)) {
        $data = [
            "message" => "Player with " . $player1Id . "does not exist",
            "success" => false,
        ];
        echo json_encode($data);
        die();
    }
    if (is_null($player2Id)) {
        $data = [
            "message" => "Player with " . $player2Id . "does not exist",
            "success" => false,
        ];
        echo json_encode($data);
        die();
    }

    //check if right player made turn

    if (/*if it is not the right players turn*/False) {
        $data = [
            "message" => "It is not your turn!",
            "success" => false,
        ];
        echo json_encode($data);
        die();
    }

    //check valid move

    if (/*if it is not a valid move*/False) {
        $data = [
            "message" => "It is not a valid move!",
            "success" => false,
        ];
        echo json_encode($data);
        die();
    }

    //check if combat and who wins

    //return data
    
} else {
    $data = [
        "success" => false,
        "message" => "Please provide a gameid",
    ];

    header('Content-Type: application/json');
    echo json_encode($data);
    die();
}