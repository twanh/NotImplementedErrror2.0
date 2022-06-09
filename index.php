<?php
    /* Header */
    $page_title = 'Stratego WP22';
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
        <?php if(!isset($_POST['name'])) {?>
        <form id="usernameForm" action="api/start_game.php" method="post" class="my-4">
            <div class="form-outline form-white mb-4">
                <label for="name" class="form-label">Username:</label>
                <input type="text" id="name" name="name" class="form-control" style="color: white;  background-color: rgba(0, 0, 0, 0);" >
            </div>
            <div class="form-group" id="color-tab">
                <p>Choose a color below:</p>
                <div>
                    <input type="radio" class="btn-check" name="color" id="red" autocomplete="off" checked="checked">
                    <label class="btn btn-danger" for="red">Red</label>

                    <input type="radio" class="btn-check" name="color" id="blue" autocomplete="off">
                    <label class="btn btn-primary" for="blue">Blue</label>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Start a Game</div>
        </form>
    </div>
</div>
<?php
    
    //testing for php and json
    //include __DIR__ . '/scripts/save_to_board.php';

    include __DIR__ . '/tpl/body-end.php';
    /* Footer */
    include __DIR__ . '/tpl/footer.php';
?>