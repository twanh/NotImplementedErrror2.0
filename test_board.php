<?php
    /* Header */
    $page_title = 'Stratego WP22';
    //include __DIR__ . '/tpl/head.php';
    include __DIR__ . '/tpl/head_game.php';
    /* Body */
    include __DIR__ . '/tpl/body-start.php';
?>
<?php
include __DIR__ . '/tpl/board.php';
?>
<?php
    
    //testing for php and json
    //include __DIR__ . '/scripts/save_to_board.php';

    //testing for board
    //include __DIR__ . '/tpl/board.php';

    include __DIR__ . '/tpl/body-end.php';
    /* Footer */
    //include __DIR__ . '/tpl/footer.php';
?>