<?php

require __DIR__ . '/../classes/Board.php';
require __DIR__ . '/../db/Database.php';

if($_POST["red-ready"] === 'true' && $_POST["blue-ready"] === 'true' && isset($_POST["gameid"])) {
    $board = getBoard($_POST["gameid"]);
    for ($y = 0; $y < 10; $y++) {
        if ($board[$y]) {
            echo("foobar");
        }
    }
} else {
    echo("Not all players are ready yet!");
}