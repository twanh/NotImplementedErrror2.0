<?php

require __DIR__ . '/../classes/Board.php';
require __DIR__ . '/../db/Database.php';

if (isset($_POST['gameid']) && isset($_POST['userid'])) {


    $gameid = $_POST['gameid'];
    $userid = $_POST['userid'];

    $db = new Database('../data/database.json');
    $board = $db->getBoard($gameid)->getBoardForPlayer($userid);


    $lastHit = null;
    list($hitByPlayer, $piece) = $db->getLastHitForGame($gameid);
    if (!is_null($hitByPlayer) and !is_null($piece)) {
        if ($hitByPlayer !== $userid) {
            $lastHit = $piece;
            $db->setLastHitForGame($gameid, NULL, NULL);
        }
    }

    $data = [
        "message" => "Everything is fine",
        "success" => true,
        "gameid" => $gameid,
        "userid" => $userid,
        "board" => $board,
        "lastHit" => $lastHit,
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
