<?php
    /* Header */
    $page_title = 'Stratego WP22 - Create Game';
    include __DIR__ . '/tpl/head.php';
    /* Body */
    include __DIR__ . '/tpl/body-start.php';
?>
<div class="row my-4">
    <div class="col-md-12 bg-dark rounded">
        <!-- if player wins show win screen -->
        <svg viewBox="0 0 500 100">
            <path id="curve" d="M73.2,148.6c4-6.1,65.5-96.8,178.6-95.6c111.3,1.2,170.8,90.3,175.1,97" />
            <text width="500">
                <textPath id="victory"startOffset="28%" salignment-baseline="top" xlink:href="#curve">
                    Victory!
                </textPath>
            </text>
        </svg>
        <h4 class="my-4">After a heroic battle your troops have won! You can sit still or battle on!</h4>
        <a href="/" class="btn btn-success btn-block">Battle on!</a>
        <!-- if player loses show win screen -->
        <svg viewBox="0 0 500 100">
            <path id="curve" d="M73.2,148.6c4-6.1,65.5-96.8,178.6-95.6c111.3,1.2,170.8,90.3,175.1,97" />
            <text width="500">
                <textPath id="defeat" startOffset="29%" salignment-baseline="top" xlink:href="#curve">
                    Defeat!
                </textPath>
            </text>
        </svg>
        <h4 class="my-4">After a hard defeat your troops have lost! You can still try and reclaim your honor and battle on!</h4>
        <a href="/" class="btn btn-success btn-block">Battle on!</a>
    </div>
</div>
<?php
    include __DIR__ . '/tpl/body-end.php';
    /* Footer */
    include __DIR__ . '/tpl/footer.php';
?>