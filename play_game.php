<?php

/* Header */
$page_title = 'Stratego WP22 - Game';
include __DIR__ . '/tpl/head_game.php';
/* Body */
include __DIR__ . '/tpl/body-start.php';

include  __DIR__ . '/db/Database.php';

$userId = $_GET['userid'];
$gameid = $_GET['gameid'];

$db = new Database('./data/database.json');
$game = $db->getGameById($gameid);

$otheruserName = NULL;
if ($userId === $game['player1Id']) {
    $otheruserName = $db->getUserById($game['player2Id'])['userName'];
} else {
    $otheruserName = $db->getUserById($game['player1Id'])['userName'];
}

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
?>
<div class="row bg-success mt-2 justify-content-center">
    <div class="col-md-12" style="display:none" id="turn-container">
        <p id="your-turn" class="text-center">It is your turn!</p>
        <p id="their-turn" class="text-center">
            It is <?php echo $otheruserName ?>'s turn!
        </p>
    </div>
</div>

<script src="scripts/game.js"></script>
<script src="scripts/play_game.js"></script>
<script src="scripts/hide_part_column.js"></script>
<script>
    $(function() {
        play();
    })
</script>
<?php
include __DIR__ . '/tpl/body-end.php';
?>
