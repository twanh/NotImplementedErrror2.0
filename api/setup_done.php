<?php

require __DIR__ . '/../classes/Board.php';
require __DIR__ . '/../db/Database.php';

$data = [
    "success" => null,
    "message" => null,
];

if(($_POST["red-ready"] === 'true' || $_POST["blue-ready"] === 'true') && isset($_POST["gameid"])) {
    $database = new Database('../data/database.json');
    $board = $database->getBoard($_POST["gameid"]);
    $correct = true;
    for ($y = 0; $y < 10; $y++) {
        $count_filled = 0;
        for ($x = 0; $x < 10; $x++) {
            if ($board[$y][$x] !== NULL || $board[$y][$x] !== "WATER") {
                $count_filled = $count_filled + 1;
            }
        }
        if (($y < 4 || $y > 5) && $count_filled !== 10) {
            $correct = false;
        } else if (($y === 4 || $y === 5) && $count_filled !== 0) {
            $correct = false;
        }
    }
    if ($correct) {
        $data = [
            "success" => true,
            "message" => "Pieces OK",
        ];
    } else {
        $data = [
            "success" => false,
            "message" => "Invalid amount of pieces!",
        ];
    }
}

if ($_POST["red-ready"] === 'true' && $_POST["blue-ready"] === 'true' && $data["success"] === true) {
    $data = [
        "success" => true,
        "message" => "All players OK: Ready to play",
    ];
} else {
    $data = [
        "success" => false,
        "message" => "Not all players are ready yet!",
    ];
}
header('Content-Type: application/json');
echo json_encode($data);
die();
?>