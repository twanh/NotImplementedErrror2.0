<?php

require __DIR__ . '/../classes/Board.php';
require __DIR__ . '/../db/Database.php';

if (isset($_POST['gameid']) && isset($_POST['userid'])) {


    $gameid = $_POST['gameid'];
    $userid = $_POST['userid'];

    $db = new Database('../data/database.json');
    // TODO: Make sure that the player only gets back it's own
    //       pieces that are located on the board.
    //$board = $db->getGameById($gameid)['board'];
    $board = $db->getBoard($gameid)->getBoardForPlayer($userid);


    $data = [
        "message" => "Everything is fine",
        "success" => true,
        "gameid" => $gameid,
        "userid" => $userid,
        "board" => $board,
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
