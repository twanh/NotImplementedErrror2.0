<?php
if (isset($_POST['name']) && isset($_POST['color'])) {
    $json_file = file_get_contents("../data/board.json");
    $boards = json_decode($json_file, true);
    $gameid = uniqid();
    $userid = uniqid();
    echo json_encode(["test" => 0]);
    if ($_POST['color'] === "red") {
        array_push($boards, [
            "id" => $gameid,
            "board" => [],
            "player1Name" => $_POST['name'],
            "player1ID" => $userid,
            "player2Name" => "",
            "player2ID" => ""
        ]);
    } else {
        array_push($boards, [
            "id" => $gameid,
            "board" => [],
            "player1Name" => "",
            "player1ID" => "",
            "player2Name" => $_POST['name'],
            "player2ID" => $userid
        ]);
    }
    echo json_encode(["test" => 1]);
    $json_file = fopen('../data/board.json', 'w');
    fwrite($json_file, json_encode($boards));
    fclose($json_file);
    $out = [
        "gameid" => $gameid,
        "userid" => $userid
    ];
    echo json_encode($out);
} else {
    return false;
}