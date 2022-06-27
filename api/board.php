<?php

require __DIR__ . '/../classes/Board.php';
require __DIR__ . '/../db/Database.php';

if (isset($_POST['gameid']) && isset($_POST['userid'])) {


    $gameid = $_POST['gameid'];
    $userid = $_POST['userid'];

    $db = new Database('../data/database.json');
    $boardGeneral = $db->getBoard($gameid)->getBoard();
    $board = $db->getBoard($gameid)->getBoardForPlayer($userid);

    $lastHit = null;
    list($hitByPlayer, $piece) = $db->getLastHitForGame($gameid);
    if (!is_null($hitByPlayer) and !is_null($piece)) {
        if ($hitByPlayer !== $userid) {
            $lastHit = $piece;
            $db->setLastHitForGame($gameid, NULL, NULL);
        }
    }

    $winner = $db->getWinner($gameid);
    if (!is_null($winner)) {

        $youWon = false;
        if ($winner === $userid) {
            $youWon = true;
        }

        $data = [
            "message" => "Everything is fine",
            "success" => true,
            "gameid" => $gameid,
            "userid" => $userid,
            "board" => $board,
            "lastHit" => $lastHit,
            "isWinner" => true,
            "youWon" => $youWon,
        ];

        header('Content-Type: application/json');
        echo json_encode($data);
        die();

    }

    /**
     * WIN CONDITION
     */
    $ownPieces = [];
    $otherPieces = [];
    $otherId = "";
    $win = ["status" => false, "winner" => null, "loser" => null,];
    /**
     * Get all the players' piece values for checking.
     */
    for ($y = 0; $y < 10; $y++) {
        for ($x = 0; $x < 10; $x++) {
            $curPiece = $boardGeneral[$y][$x];
            if ($curPiece !== null) {
                if ($curPiece !== "WATER") {
                    if ($curPiece->getOwnerId() === $userid) {
                        $ownPieces[] = $curPiece->getValue();
                    } else {
                        $otherPieces[] = $curPiece->getValue();
                        if ($otherId !== "") {
                            $otherId = $curPiece->getOwnerId();
                        }
                    }
                }
            }
        }
    }
    /**
     * For each player, check if:
     * 1. Their army contains a flag;
     * 2. Their army contains pieces that are something other than a bomb or a flag.
     * If it satisfies both conditions, then the game is not lost.
     */
    function checkIfLost(array $arrayIn, string $idIn, array $winCond): array
    {
        $lost = true;
        if (in_array("F", $arrayIn)) {
            for ($i = 0; $i < count($arrayIn); $i++) {
                $curPiece = $arrayIn[$i];
                if (!in_array($curPiece, ["B", "F"])) {
                    $lost = false;
                    break;
                }
            }
        }
        if ($lost) {
            $winCond["loser"] = $idIn;
            $winCond["status"] = true;
        }
        return $winCond;
    }
    /**
    * Perform checkIfLost on both players, and assign a winner if a loser is assigned.
    */
    $win = checkIfLost($ownPieces, $userid, $win);
    if (isset($win["loser"])) {
        $win["winner"] = $otherId;
    } else {
        $win = checkIfLost($otherPieces, $otherId, $win);
        if (isset($win["loser"])) {
            $win["winner"] = $userid;
        }
    }

    $data = [
        "message" => "Everything is fine",
        "success" => true,
        "gameid" => $gameid,
        "userid" => $userid,
        "board" => $board,
        "lastHit" => $lastHit,
        "winner" => $win,
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
