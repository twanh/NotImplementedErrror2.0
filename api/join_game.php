<?php

require __DIR__ . '/../classes/Board.php';
require __DIR__ . '/../db/Database.php';

if (isset($_POST['name']) && isset($_GET['gameid'])) {

    echo "WELCOME";
    $gameid = $_GET['gameid'];
    $userid = uniqid();

    $db = new Database('../data/database.json');
    $game = $db->getGameById($gameid);
    if (is_null($game)) {
        echo "ERROR: Could not find a game with id" . $gameid . "!";
    }

    if(!is_null($game['player1Id'])) {
        $player2Name = $_POST['name'];
        $player2Id = $userid;
        $u_ret = $db->addUser($userid, $player2Name, [$gameid]);
        $ret = $db->updateGame($gameid, null, $userid, null);
    } else if (!is_null($game['player2Id'])) {
        $player1Name = $_POST['name'];
        $player1Id = $userid;
        $u_ret = $db->addUser($userid, $player1Name, [$gameid]);
        $ret = $db->updateGame($gameid, $userid, null, null);
    } else {
        echo "ERROR: Game full!";
    }

    if (!$u_ret) {
        echo "ERROR: Could not create new user!";
    }

    if (!$ret) {
        echo "Could not update game!";
    }

    header("Location: ../lobby.php?gameid=" . $gameid . "&userid=" . $userid);
    die();
} else {
    return false;
}
