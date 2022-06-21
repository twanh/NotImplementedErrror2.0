<?php

require __DIR__ . '/../classes/Board.php';
require __DIR__ . '/../db/Database.php';

if (isset($_POST['gameid']) && isset($_POST['userid']) && isset($_POST['cux_y']) && isset($_POST['cur_x']) && isset($_POST['direction'])) {

    $gameid = $_POST['gameid'];
    $userid = $_POST['userid'];
    $cur_y = $_POST['cur_y'];
    $cur_x = $_POST['cur_x'];

    $db = new Database('../data/database.json');
    $game = $db->getGameById($gameid);
    $board = $db->getBoard($gameid);


    // Make sure that both players are ready
    // TODO: Add `ready` to db when both players are ready (see: setup_done.php)
    if (is_null($game['player1Id']) or is_null($game['player2Id'])) {
        $data = [
            'success' => false,
            'message' => "Make sure that both player have joined",
        ];
        header('Content-Type: application/json');
        echo json_encode($data);
        die();
    }

    // Make sure that the player is moving a piece of their own.
    $curPiece = $board->getPositionOnBoard($cur_y, $cur_x);
    if ($curPiece->getGameById($userid) !== $userid) {
        $data = [
            'success' => false,
            'message' => "You can only move your own pieces!",
        ];
        header('Content-Type: application/json');
        echo json_encode($data);
        die();
    }

    $distance = NULL;
    if (isset($_POST['distance'])) {
        $distance = $_POST['distance'];

    }

    $direction = $_POST["direction"];
    if (!is_null($distance)) {
        if ($direction == "up") {
            $board->moveUp($cur_y, $cur_x, $distance);
        } else if ($direction == "down") {
            $board->moveDown($cur_y, $cur_x, $distance);
        } else if ($direction == "left") {
            $board->moveLeft($cur_y, $cur_x, $distance);
        } else if ($direction == "right") {
            $board->moveRight($cur_y, $cur_x, $distance);
        }
    } else {
        if ($direction == "up") {
            $board->moveUp($cur_y, $cur_x);
        } else if ($direction == "down") {
            $board->moveDown($cur_y, $cur_x);
        } else if ($direction == "left") {
            $board->moveLeft($cur_y, $cur_x);
        } else if ($direction == "right") {
            $board->moveRight($cur_y, $cur_x);
        }
    }

    if ($move) {
        $data = [
            'success' => true,
        ];
        // Update the board (in the db) for this game.
        $db->updateGame($gameid, NULL, NULL, $board->getBoard());
    } else {
        $data = [
            'success' => false,
            'message' => "You cannot move this piece there!"
        ];
    }

    // TODO: Check it's the players turn (at the start)
    // and swap the turn if the move was successfull!
    /* $db->setTurnForGame() */


    header('Content-Type: application/json');
    echo json_encode($data);
    die();

} else {

    $data = [
        'message' => "Please provide gameid and userid.",
        'success' => false,
    ];

    header('Content-Type: application/json');
    echo json_encode($data);
    die();

}
