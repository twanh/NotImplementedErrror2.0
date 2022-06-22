<?php
    /* Header */
    $page_title = 'Stratego WP22 - Create Game';
    include __DIR__ . '/tpl/head.php';
    /* Body */
    include __DIR__ . '/tpl/body-start.php';
?>
<div class="row my-4">
    <div class="col-md-12 bg-dark rounded">
        <h1 class="text-center">Stratego WP22</h1>
    </div>
</div>
<div class="row my-4">
    <div class="col-md-12 bg-dark rounded">
        <form id="usernameForm" action="api/start_game.php" method="post" class="my-4">
            <div class="form-outline form-white mb-4">
                <label for="name" class="form-label">Username:</label>
                <input type="text" id="name" name="name" class="form-control">
            </div>
            <div class="form-group mb-4" id="color-tab">
                <p>Choose a color below:</p>
                <div class="btn-group btn-group-toggle" data-toggle="buttons">
                <label class="btn btn-danger button active">
                    <input type="radio" name="color" id="red" value="red" checked> Red
                </label>
                <label class="btn btn-primary button">
                    <input type="radio" name="color" id="blue" value="blue"> Blue
                </label>
            </div>
            <br/>
            <div id="submit" class="btn btn-primary mt-4">Start a Game</div>
        </form>
    </div>
</div>
<script src="scripts/login.js"></script>
<?php
    
    //testing for php and json
    //include __DIR__ . '/scripts/save_to_board.php';

    //testing for board
    //include __DIR__ . '/tpl/board.php';

    include __DIR__ . '/tpl/body-end.php';
    /* Footer */
    include __DIR__ . '/tpl/footer.php';
?>