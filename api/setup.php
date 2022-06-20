<?php

use board\Board;

require __DIR__ . '/../classes/Board.php';
require __DIR__ . '/../classes/Pieces.php';
require __DIR__ . '/../db/Database.php';

if (isset($_POST['gameid']) && isset($_POST['userid']) && isset($_POST['board'])) {

    $gameid = $_POST['gameid'];
    $userid = $_POST['userid'];
    $userBoard = json_decode($_POST['board']);
    


    $db = new Database('../data/database.json');
    $game = $db->getGameById($gameid);
    $gameBoard = $db->getBoard($gameid);


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


    // First use a new board to test if we can move somewhere before
    // actually moving there!
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


    // Validate board
    // - Make sure that if player is red, they only have pieces on the bottom
    //   half & vise versa
    // - All pieces are used
    // - No extra pieces (so only 1 flag, 6 bombs etc...)

    // Save board to the database with all correct pieces
    if ($userid === $game['player1Id']) {
        // User is red
        $piecesAmount = [];

        // Only loop over the bottom rows where the red player can place
        // its pieces
        for ($y = 4; $y < 10; $y++) {
            for($x = 0; $y < 10; $x++) {
                $curPiece = $userBoard[$y][$x];
                if ($curPiece['player'] !== $userid) {
                    // TODO: Return error -- cannot setup for other player.
                    echo "DEBUG: Not same userid";
                    die();
                }
                $newPiece = \pieces\Piece::fromPieceName($curPiece['name'], $userid);
                $canMove = $validationBoard->setPieceOnPosition($newPiece, $y, $x, true);
                if (!$canMove) {
                    // TODO: Error
                    echo "DEBUG: Cannot move!";
                    die();
                }
                
                // Count the amount of pieces
                if (array_key_exists($newPiece->getName(), $piecesAmount)) {
                    $piecesAmount[$newPiece->getName()] = $piecesAmount[$newPiece->getName()] + 1;
                } else {
                    $piecesAmount[$newPiece->getName()] = 1;
                }

            }
        }

        foreach ($piecesCount as $piece => $count) {
            if (!array_key_exists($piece, $piecesAmount)) {
                // TODO: Handle error
                echo "DEBUG: Player has not used Piece: " . $piece;
                die();
            }

            if ($piecesAmount[$piece] !== $count) {
                // TODO: Handle error
                echo "DEBUG: Player did not use correct amount of pieces for Piece: " . $piece;
                die();
            }

        }

        // If everything is valid, actually update the board.
        for ($y = 4; $y < 10; $y++) {
            for($x = 0; $y < 10; $x++) {
                $newPiece = \pieces\Piece::fromPieceName($curPiece['name'], $userid);
                $gameBoard->setPieceOnPosition($newPiece, $y, $x, true);
            }
        }
        // Save the updated board in the DB.
        $db->updateGame($gameid, NULL, NULL, $gameBoard->getBoard());
    } else {
        // TODO: DRY!!
        // User is blue
        $piecesAmount = [];

        // Only loop over the bottom rows where the red player can place
        // its pieces
        for ($y = 0; $y < 5; $y++) {
            for($x = 0; $y < 10; $x++) {
                $curPiece = $userBoard[$y][$x];
                if ($curPiece['player'] !== $userid) {
                    // TODO: Return error -- cannot setup for other player.
                    echo "DEBUG: Not same userid";
                    die();
                }
                $newPiece = \pieces\Piece::fromPieceName($curPiece['name'], $userid);
                $canMove = $validationBoard->setPieceOnPosition($newPiece, $y, $x, true);
                if (!$canMove) {
                    // TODO: Error
                    echo "DEBUG: Cannot move!";
                    die();
                }
                
                // Count the amount of pieces
                if (array_key_exists($newPiece->getName(), $piecesAmount)) {
                    $piecesAmount[$newPiece->getName()] = $piecesAmount[$newPiece->getName()] + 1;
                } else {
                    $piecesAmount[$newPiece->getName()] = 1;
                }

            }
        }

        foreach ($piecesCount as $piece => $count) {
            if (!array_key_exists($piece, $piecesAmount)) {
                // TODO: Handle error
                echo "DEBUG: Player has not used Piece: " . $piece;
                die();
            }

            if ($piecesAmount[$piece] !== $count) {
                // TODO: Handle error
                echo "DEBUG: Player did not use correct amount of pieces for Piece: " . $piece;
                die();
            }

        }

        // If everything is valid, actually update the board.
        for ($y = 4; $y < 10; $y++) {
            for($x = 0; $y < 10; $x++) {
                $newPiece = \pieces\Piece::fromPieceName($curPiece['name'], $userid);
                $gameBoard->setPieceOnPosition($newPiece, $y, $x, true);
            }
        }
        // Save the updated board in the DB.
        $db->updateGame($gameid, NULL, NULL, $gameBoard->getBoard());
    }

    // TODO: Set readyPlayer1/2 to true in DB.


    $data = [
        'success' => true,
    ];

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
