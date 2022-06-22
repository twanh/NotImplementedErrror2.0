<?php
    /* Header */
    $page_title = 'Stratego WP22 - Rules';
    include __DIR__ . '/tpl/head.php';
    /* Body */
    include __DIR__ . '/tpl/body-start.php';
?>
<style>
body {
    overflow-y: scroll;
}
</style>
<div class="row my-4">
    <div class="col-md-12 bg-dark rounded">
        <h1 class="text-center">Stratego Rules</h1>
    </div>
</div>
    <h2 class="text-center">Pieces</h2>
    <p class="text-center">In descending rank, the pieces are as follows:</p>
<div class="row my-4">
    <div class="card bg-dark m-4" style="width: 14rem;">
        <div class="card-img-top img-1 mx-auto mt-2" style="width: 10rem; height: 10rem;"></div>
        <div class="card-body">
            <h5>Marshal</h5>
            <p class="card-text bg-dark">
                Amount: 1
            </p>
        </div>
    </div>
    <div class="card bg-dark m-4" style="width: 14rem;">
        <div class="card-img-top img-2 mx-auto mt-2" style="width: 10rem; height: 10rem;"></div>
        <div class="card-body">
            <h5>General</h5>
            <p class="card-text bg-dark">
                Amount: 1
            </p>
        </div>
    </div>
    <div class="card bg-dark m-4" style="width: 14rem;">
        <div class="card-img-top img-3 mx-auto mt-2" style="width: 10rem; height: 10rem;"></div>
        <div class="card-body">
            <h5>Colonel</h5>
            <p class="card-text bg-dark">
                Amount: 2
            </p>
        </div>
    </div>
    <div class="card bg-dark m-4" style="width: 14rem;">
        <div class="card-img-top img-4 mx-auto mt-2" style="width: 10rem; height: 10rem;"></div>
        <div class="card-body">
            <h5>Major</h5>
            <p class="card-text bg-dark">
                Amount: 3
            </p>
        </div>
    </div>
    <div class="card bg-dark m-4" style="width: 14rem;">
        <div class="card-img-top img-5 mx-auto mt-2" style="width: 10rem; height: 10rem;"></div>
        <div class="card-body">
            <h5>Captain</h5>
            <p class="card-text bg-dark">
                Amount: 4
            </p>
        </div>
    </div>
    <div class="card bg-dark m-4" style="width: 14rem;">
        <div class="card-img-top img-6 mx-auto mt-2" style="width: 10rem; height: 10rem;"></div>
        <div class="card-body">
            <h5>Lieutenant</h5>
            <p class="card-text bg-dark">
                Amount: 4
            </p>
        </div>
    </div>
    <div class="card bg-dark m-4" style="width: 14rem;">
        <div class="card-img-top img-7 mx-auto mt-2" style="width: 10rem; height: 10rem;"></div>
        <div class="card-body">
            <h5>Sergeant</h5>
            <p class="card-text bg-dark">
                Amount: 4
            </p>
        </div>
    </div>
    <div class="card bg-dark m-4" style="width: 14rem;">
        <div class="card-img-top img-8 mx-auto mt-2" style="width: 10rem; height: 10rem;"></div>
        <div class="card-body">
            <h5>Miner</h5>
            <p class="card-text bg-dark">
                Amount: 5<br>
                Extra: Can defuse bombs
            </p>
        </div>
    </div>
    <div class="card bg-dark m-4" style="width: 14rem;">
        <div class="card-img-top img-9 mx-auto mt-2" style="width: 10rem; height: 10rem;"></div>
        <div class="card-body">
            <h5>Scout</h5>
            <p class="card-text bg-dark">
                Amount: 8<br>
                Extra: Can move any amount of spaces
            </p>
        </div>
    </div>
    <div class="card bg-dark m-4" style="width: 14rem;">
        <div class="card-img-top img-spy mx-auto mt-2" style="width: 10rem; height: 10rem;"></div>
        <div class="card-body">
            <h5>Spy</h5>
            <p class="card-text bg-dark">
                Amount: 1<br>
                Extra: Can defeat the Marshal if the Spy attacks
            </p>
        </div>
    </div>
    <div class="card bg-dark m-4" style="width: 14rem;">
        <div class="card-img-top img-bomb mx-auto mt-2" style="width: 10rem; height: 10rem;"></div>
        <div class="card-body">
            <h5>Bomb</h5>
            <p class="card-text bg-dark">
                Amount: 6<br>
                Extra: Cannot move, kills everyone except the Miner
            </p>
        </div>
    </div>
    <div class="card bg-dark m-4" style="width: 14rem;">
        <div class="card-img-top img-flag mx-auto mt-2" style="width: 10rem; height: 10rem;"></div>
        <div class="card-body">
            <h5>Flag</h5>
            <p class="card-text bg-dark">
                Amount: 1<br>
                Extra: Cannot move, capture to win
            </p>
        </div>
    </div>
</div>
<div class="container">
    <h2 class="text-center">Setup</h2>
    <div class="row my-4">
        <p class="text-justify">
            When starting a game, you must place all 40 of your pieces in the four
            rows given to you. Pay attention to how you place them: a good initial
            setup can make or break a game. Red makes the first move when both
            players are done with their setup.
        </p>
    </div>
    <h2 class="text-center">Movement</h2>
    <div class="row my-4">
        <p class="text-justify">
            When it is your turn, you may move any movable piece belonging to
            you. In general, you may move one space in one of four directions:
            left, right, up or down. You may not move diagonally, into water or
            over/on top of another piece. You may also not move between the
            same two spaces for more than 5 moves.
        </p>
        <p class="text-justify">
            Scouts may move an unlimited amount of spaces, but in only one
            direction and without obstructions. Bombs and the flag may not be
            moved.
        </p>
    </div>
    <h2 class="text-center">Attack</h2>
    <div class="row my-4">
        <p class="text-justify">
            If you are next to an enemy piece after making your move, you may
            attack the other piece. You compare the ranks of the two pieces
            to each other, and in general, the higher rank beats the lower
            rank. The losing piece is removed from the board: if the
            attacking piece wins, then it occupies the losing piece's space;
            if the defending piece wins, it stays put.
        </p>
        <p class="text-justify">
            Every piece is defeated by bombs, except miners: they can defuse
            bombs and then take their place. The spy loses to everyone, unless
            he attacks the marshal, in which case the marshal is defeated.
            However, should the marshal attack the spy, then the spy is
            defeated.
        </p>
        <p class="text-justify">
            If you attack the flag, no matter with what piece, you win the game.
        </p>
    </div>
</div>
<p>easter egg</p>

<?php
    
    //testing for php and json
    //include __DIR__ . '/scripts/save_to_board.php';

    //testing for board
    //include __DIR__ . '/tpl/board.php';

    include __DIR__ . '/tpl/body-end.php';
    /* Footer */
    include __DIR__ . '/tpl/footer.php';
?>