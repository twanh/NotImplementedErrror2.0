<?php
    /* Header */
    $page_title = 'Webprogramming Assignment 2';
    $navigation = Array(
        'active' => 'Home',
        'items' => Array(
        'Home' => '/WP22/assignment_2/index.php',
        'Links' => '/WP22/assignment_2/links.php',
        'News' => '/WP22/assignment_2/news.php',
        'Future' => '/WP22/assignment_2/future.php'
    )
    );
    include __DIR__ . '/tpl/head.php';
    /* Body */
    include __DIR__ . '/tpl/body-start.php';
?>
<div class="row">
    <div class="col-md-12">
        <h1>Stratego WP22</h1>
    </div>
</div>
<?php
    include __DIR__ . '/tpl/board.php';
    include __DIR__ . '/tpl/body-end.php';
    /* Footer */
    include __DIR__ . '/tpl/footer.php';
?>