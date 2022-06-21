<?php

/* Header */
$page_title = 'Stratego WP22';
include __DIR__ . '/tpl/head_game.php';
/* Body */
include __DIR__ . '/tpl/body-start.php';

include  __DIR__ . '/db/Database.php';

$userId = $_GET['userid'];
$gameid = $_GET['gameid'];

$db = new Database('./data/database.json');
$game = $db->getGameById($gameid);

// Make sure this game exists
if (is_null($game)) {
    die("The game you are trying to join does not exist. Make sure to use an invite link or make one!");
}

// Make sure that the current user is player1 or player2
if ($game['player1Id'] !== $userId and $game['player2Id'] !== $userId) {
    die("It seems like you do not belong in this game, make sure the invite link is correct.");
}

?>
<?php
include __DIR__ . '/tpl/board.php';
// TODO: get setup of players
?>
<script src="scripts/drag_drop_play_game.js"></script>
<?php
include __DIR__ . '/tpl/body-end.php';
?>
