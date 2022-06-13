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