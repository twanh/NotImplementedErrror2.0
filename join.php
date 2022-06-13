<?php
/* Header */
$page_title = 'Stratego WP22';
include __DIR__ . '/tpl/head.php';
/* Body */
include __DIR__ . '/tpl/body-start.php';

$gameid = $_GET['gameid'];
?>
    <div class="row my-4">
        <div class="col-md-12 bg-dark rounded">
            <h1 class="text-center">Stratego WP22</h1>
            <p>You have been invited to join a game!</p>
        </div>
    </div>
    <div class="row my-4">
        <div class="col-md-12 bg-dark rounded">
            <form id="usernameForm" action="api/join_game.php?gameid=<?php echo $gameid ?>" method="post" class="my-4">
                <div class="form-outline form-white mb-4">
                    <label for="name" class="form-label">Username:</label>
                    <input type="text" id="name" name="name" class="form-control">
                </div>
                <div id="submit" class="btn btn-primary mt-4">Join Game</div>
            </form>
        </div>
    </div>
    <script src="scripts/join.js"></script>
<?php

//testing for php and json
//include __DIR__ . '/scripts/save_to_board.php';

//testing for board
//include __DIR__ . '/tpl/board.php';

include __DIR__ . '/tpl/body-end.php';
/* Footer */
include __DIR__ . '/tpl/footer.php';
?>