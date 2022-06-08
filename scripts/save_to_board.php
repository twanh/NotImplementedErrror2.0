<?php
    // info for php and json
    //https://www.kodingmadesimple.com/2016/05/how-to-write-json-to-file-in-php.html
    //https://www.opentechguides.com/how-to/article/php/205/php-nested-json.html
    $url = "./data/board.json";
    $board_id = "1231231"; //get / make the board id.
    $json_string = file_get_contents($url);
    $json = json_decode($json_string, true);
    foreach($json as $elem) {
        echo $elem['id'];
        if ($elem['id'] == $board_id) {
            echo $elem;
            echo $elem['board'][0][0]['player'];
        }
    }
?>