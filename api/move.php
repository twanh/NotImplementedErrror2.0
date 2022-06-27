<?php

require __DIR__ . '/../classes/Board.php';
require __DIR__ . '/../db/Database.php';

if (isset($_POST['gameid']) && isset($_POST['userid']) && isset($_POST['cur_y']) && isset($_POST['cur_x']) && isset($_POST['direction'])) {

    $gameid = $_POST['gameid'];
    $userid = $_POST['userid'];
    $cur_y = $_POST['cur_y'];
    $cur_x = $_POST['cur_x'];

    $db = new Database('../data/database.json');
    $game = $db->getGameById($gameid);
    $board = $db->getBoard($gameid);


    // Make sure that both players are ready
    if (is_null($game['player1Id']) or is_null($game['player2Id'])) {
        $data = [
            'success' => false,
            'message' => "Make sure that both player have joined",
        ];
        header('Content-Type: application/json');
        echo json_encode($data);
        die();
    }

    $isReady = $db->getReadyForGame($gameid);
    if (is_null($isReady)) {
        $data = [
            'success' => false,
            'message' => "This game does not exist!",
        ];
        header('Content-Type: application/json');
        echo json_encode($data);
        die();
    }

    if (!$isReady) {
        $data = [
            'success' => false,
            'message' => "You cannot make a move before both players are ready!",
        ];
        header('Content-Type: application/json');
        echo json_encode($data);
        die();
    }


    $turn = $db->getTurnForGame($gameid);

    if ($turn === 1) {
        if ($userid !== $game['player1Id']) {
            $data = [
                'success' => false,
                'message' => "It is not your turn!",
            ];
            header('Content-Type: application/json');
            echo json_encode($data);
            die();
        }
    } else {
        if ($userid !== $game['player2Id']) {
            $data = [
                'success' => false,
                'message' => "It is not your turn!",
            ];
            header('Content-Type: application/json');
            echo json_encode($data);
            die();
        }
    }

    // Make sure that the player is moving a piece of their own.
    $curPiece = $board->getPositionOnBoard($cur_y, $cur_x);
    if (is_null($curPiece)) {
        $data = [
            'success' => false,
            'message' => "There is no piece to move on (" . $cur_y . ',' . $cur_x . ').',
        ];
        header('Content-Type: application/json');
        echo json_encode($data);
        die();
    }
    if ($curPiece->getOwnerId() !== $userid) {
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
            list($move, $msg) = $board->moveUp($cur_y, $cur_x, $distance);
        } else if ($direction == "down") {
            list($move, $msg) = $board->moveDown($cur_y, $cur_x, $distance);
        } else if ($direction == "left") {
            list($move, $msg) = $board->moveLeft($cur_y, $cur_x, $distance);
        } else if ($direction == "right") {
            list($move, $msg) = $board->moveRight($cur_y, $cur_x, $distance);
        }
    } else {
        if ($direction == "up") {
            list($move, $msg) = $board->moveUp($cur_y, $cur_x);
        } else if ($direction == "down") {
            list($move, $msg) = $board->moveDown($cur_y, $cur_x);
        } else if ($direction == "left") {
            list($move,$msg) = $board->moveLeft($cur_y, $cur_x);
        } else if ($direction == "right") {
            list($move, $msg) = $board->moveRight($cur_y, $cur_x);
        }
    }

    if ($move) {
        $data = [
            'success' => true,
            'message' => $msg,
        ];
        // Update the board (in the db) for this game.
        $db->updateGame($gameid, NULL, NULL, $board->getBoard());
        $data['board'] = $db->getBoard($gameid)->getBoardForPlayer($userid);

        $youWin = false;
        if ($msg === 'Game Over! You hit the flag!') {
            $db->setWinner($gameid, $userid);
            $youWin = true;
        }
        $data['youWon'] = $youWin;

        if (substr($msg, 4, 3) == "hit" or substr($msg, 8, 3) == 'hit') {
            $data['hit'] = true;
            $myPiece = $curPiece->getName();
            $db->setLastHitForGame($gameid, $userid, $myPiece);
        }

        if ($turn === 1) {
            $updated = $db->setTurnForGame($gameid, 2);
        } else {
            $updated = $db->setTurnForGame($gameid, 1);
        }

    } else {
        $data = [
            'success' => false,
            'message' => $msg,
            'board' => $db->getBoard($gameid)->getBoardForPlayer($userid),
        ];
    }



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
