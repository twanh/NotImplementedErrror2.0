<?php

require __DIR__ . '/../classes/Board.php';
require __DIR__ . '/../db/Database.php';

if (isset($_POST['name']) && isset($_GET['gameid'])) {

    $gameid = $_GET['gameid'];
    $userid = uniqid();

    $board = getBoardById($gameid);
    $db = load();
    if ($board["player1Id"] !== null) {
        $player2Name = $_POST['name'];
        $player2Id = $userid;
        // TODO: Find a better way to add to gameIds
        $u_ret = $db->addUser($userid, $player2Name, [$gameid]);
        $ret = $db->updateGame($gameid, null, $userid, null);
    } else if ($board["player2Id"] !== null) {
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

    header("Location: ../game.php?gameid=" . $gameid . "&userid=" . $userid);
    die();
} else {
    return false;
}