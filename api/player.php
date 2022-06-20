<?php

require __DIR__ . '/../classes/Board.php';
require __DIR__ . '/../db/Database.php';

if (isset($_POST['gameid']) && isset($_POST['userid'])) {

    $gameid = $_POST['gameid'];
    $userid = $_POST['userid'];

    $db = new Database('../data/database.json');

    $user = $db->getUserById($userid);
    $game = $db->getGameById($gameid);

    $userColor = 'red';
    if ($userid === $game['player2Id']) {
        $userColor = 'blue';
    }

    $data = [
        'success' => true,
        'username' => $user['userName'],
        'id' => $user['id'],
        'color' => $userColor,
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
