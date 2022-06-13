<?php
/* Header */
$page_title = 'Stratego WP22';
include __DIR__ . '/tpl/head.php';
/* Body */
include __DIR__ . '/tpl/body-start.php';

include  __DIR__ . '/db/Database.php';

$userId = $_GET['userid'];
$gameid = $_GET['gameid'];

$db = new Database('./data/database.json');
$game = $db->getGameById($gameid);

// Make sure this game exists
if (is_null($game)) {
    // TODO: Display a nicer error message!
    die("This game does not exist! So you cannot play it!");
}

// Make sure that the current user is player1 or player2
if ($game['player1Id'] !== $userId and $game['player2Id'] !== $userId) {
    // TODO: Display a nicer error message!
    die("It seems like you do not belong in this game");
}

?>
    <div class="row my-4">
        <div class="col-md-12 bg-dark rounded">
            <h1 class="text-center">Stratego WP22</h1>
        </div>
    </div>
    <div class="row my-4">
        <div id="urlShare" class="col-md-12 bg-dark rounded">
            <div id="submit" class="btn btn-primary mt-4">Generate Join Link</div>
        </div>
    </div>
    <script src="scripts/genlink.js"></script>
<?php

//testing for php and json
//include __DIR__ . '/scripts/save_to_board.php';
include __DIR__ . '/tpl/board.php';
include __DIR__ . '/tpl/body-end.php';
/* Footer */
include __DIR__ . '/tpl/footer.php';
?>