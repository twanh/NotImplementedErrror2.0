<?php

require __DIR__ . '/../classes/Board.php';
require __DIR__ . '/../db/Database.php';

if (isset($_POST['name']) && isset($_POST['color'])) {

    $board = new board\Board();

    $gameid = uniqid();
    $userid = uniqid();

    $player1Name = '';
    $player1Id = null;
    $player2Name = '';
    $player2Id = null;

    $db = new Database('../data/database.json');
    if ($_POST['color'] === "red") {
        $player1Name = $_POST['name'];
        $player1Id = $userid;
        $u_ret = $db->addUser($userid, $player1Name, [$gameid]);
    } else {
        $player2Name = $_POST['name'];
        $player2Id = $userid;
        $u_ret = $db->addUser($userid, $player2Name, [$gameid]);
    }

    if (!$u_ret) {
        echo "ERROR: Could not create new user!";
    }

    $ret = $db->addGame($gameid, $player1Id, $player2Id, $board->getBoard());

    if (!$ret) {
        echo "Could not create game!";
    }

   header("Location: ../lobby.php?gameid=" . $gameid . "&userid=" . $userid);
   die();
} else {
    return false;
}
