<?php
    /* Header */
    $page_title = 'Stratego WP22';
    include __DIR__ . '/tpl/head.php';
    /* Body */
    include __DIR__ . '/tpl/body-start.php';
?>
<div class="row">
    <div class="col-md-12">
        <h1>Stratego WP22</h1>
        <form id="usernameForm" action="game.php" method="post">
            <div class="form-group">
                <label for="name">Username</label>
                <input type="text" id="name" name="name" class="form-control">
            </div>
            <div class="form-group" id="color-tab">
                <p>Choose a color below:</p>
                <div>
                    <input type="radio" id="red" checked="checked"
                           name="color" value="red">
                    <label for="red" style="color: red;">Red</label>
                    <input type="radio" id="blue"
                           name="color" value="blue">
                    <label for="blue" style="color: blue;">Blue</label>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Start a Game</div>
        </form>
    </div>
</div>
<?php
    
    //testing for php and json
    include __DIR__ . '/scripts/save_to_board.php';

    include __DIR__ . '/tpl/body-end.php';
    /* Footer */
    include __DIR__ . '/tpl/footer.php';
?>