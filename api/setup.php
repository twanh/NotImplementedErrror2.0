<?php

use board\Board;

require __DIR__ . '/../classes/Board.php';
require_once __DIR__ . '/../classes/Pieces.php';
require __DIR__ . '/../db/Database.php';

// Player: 1 -> red & 2 -> blue
function validateBoard($userBoard, $player, $userid) {

    // Validate board
    // - Make sure that if player is red, they only have pieces on the bottom
    //   half & vise versa
    // - All pieces are used
    // - No extra pieces (so only 1 flag, 6 bombs etc...)

    $errors = [];

    $validationBoard = new Board();

    $piecesCount = [
        "Flag" => 1,
        "Spy" => 1,
        "Bomb" => 6,
        "Scout" => 8,
        "Miner" => 5,
        "Sergeant" => 4,
        "Lieutenant" => 4,
        "Captain" => 4,
        "Major" => 3,
        "Colonel" => 2,
        "General" => 1,
        "Marshal" => 1,
    ];


    $piecesAmount = [];


    for ($y = 0; $y < 10; $y++) {

        // Sometimes the client does not pad the board
        // with null, so skip empty rows.
        if (count($userBoard[$y]) === 0) {
            continue;
        }

        for ($x = 0; $x < 10; $x++) {

            $curPiece = $userBoard[$y][$x];

            // Skip empty spots and water -- these do not
            // contribute to the piece count.
            if (is_null($curPiece) or $curPiece === "WATER") {
                continue;
            }

            // Check if the current piece is on the right side of
            // the board (for red and blue)

            if ($player === 'blue') {
                if ($y > 3) {
                    $errors[] = 'Pieces should be played on own half';
                }
            } else {
                if ($y <= 5) {
                    $errors[] = 'Pieces should be played on own half';
                }
            }


            if ($curPiece['player'] !== $userid) {
                $errors[] = 'Piece on position (' . $y . ',' .
                    $x . ') does not have the correct user id';
                continue;
            }

            if (is_null($curPiece['piece'])) {
                $errors[] = 'Piece on position (' . $y . ',' . $x . ')' . ' cannot have piece name NULL';
                continue;
            }

            $newPiece = \pieces\Piece::fromPieceName($curPiece['piece'], $userid);
            list($canMove, $msg) = $validationBoard->setPieceOnPosition($newPiece, $y, $x, true);
            if (!$canMove) {
                $errors[] = 'Piece on position (' . $y . ',' .
                    $x . ') cannot be placed there!' . $msg;
                continue;
            }


            // Keep track of the used pieces.
            if (array_key_exists($newPiece->getName(), $piecesAmount)) {
                $piecesAmount[$newPiece->getName()] = $piecesAmount[$newPiece->getName()] + 1;
            } else {
                $piecesAmount[$newPiece->getName()] = 1;
            }

        }
    }


    foreach ($piecesCount as $piece => $count) {
        if (!array_key_exists($piece, $piecesAmount)) {
            $errors[] = 'Player did not use piece ' . $piece . '.';
            continue;
        }

        if ($piecesAmount[$piece] !== $count) {
            $errors[] = 'Player did not use correct amount of piece ' . $piece . '.';
        }
    }

    if (count($errors) === 0) {
        return array(true, $errors);
    }

    return array(false, $errors);


}

if (isset($_POST['gameid']) && isset($_POST['userid']) && isset($_POST['board'])) {

    $gameid = $_POST['gameid'];
    $userid = $_POST['userid'];
    // TODO: Find a way around the double decode!
    /* $userBoard = json_decode(json_decode($_POST['board'], true), true); */
    $userBoard = json_decode($_POST['board'], true);

    $db = new Database('../data/database.json');
    $game = $db->getGameById($gameid);
    $gameBoard = $db->getBoard($gameid);


    if (is_null($game)) {

        $data = [
            'success'=> false,
            'message'=> "Make sure the game exists",
        ];
        header('Content-Type: application/json');
        echo json_encode($data);
        die();
    }

    // Make sure that both players have joined
    if (is_null($game['player1Id']) or is_null($game['player2Id'])) {
        $data = [
            'success' => false,
            'message' => "Make sure that both player have joined",
        ];
        header('Content-Type: application/json');
        echo json_encode($data);
        die();
    }


    $data = [];


    // Save board to the database with all correct pieces
    if ($userid === $game['player1Id']) {
        // User is red

        list($valid, $errors) = validateBoard($userBoard, 'red', $userid);

        if ($valid) {
            // If everything is valid, actually update the board.
            for ($y = 6; $y < 10; $y++) {
                for($x = 0; $x < 10; $x++) {
                    $curPiece = $userBoard[$y][$x];

                    $newPiece = \pieces\Piece::fromPieceName($curPiece['piece'], $userid);
                    $gameBoard->setPieceOnPosition($newPiece, $y, $x, true);
                }
            }
            // Save the updated board in the DB.
            $db->updateGame($gameid, NULL, NULL, $gameBoard->getBoard());

        } 

        $data = [
            'success' => $valid,
            'errors' => $errors,
        ];


    } else {
        // User is blue
        list($valid, $errors) = validateBoard($userBoard, 'blue', $userid);

        if ($valid) {
            // If everything is valid, actually update the board.
            for($y = 0; $y < 4; $y++) {
                for($x = 0; $x < 10; $x++) {
                    $curPiece = $userBoard[$y][$x];
                    $newPiece = \pieces\Piece::fromPieceName($curPiece['piece'], $userid);
                    $gameBoard->setPieceOnPosition($newPiece, $y, $x, true);
                }
            }
            // Save the updated board in the DB.
            $db->updateGame($gameid, NULL, NULL, $gameBoard->getBoard());

            }

        $data = [
            'success' => $valid,
            'errors' => $errors,
        ];
    }

    $db->setReadyForGame($gameid, $userid);

    $data['ready'] = $db->getReadyForGame($gameid);
    
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
