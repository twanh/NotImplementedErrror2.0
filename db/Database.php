<?php

class Database
{
    public $file;
    public $open; // To check if the file is currently opened.

    // Load for file, or perhaps id
    public function __construct($file)
    {
        $this->file = $file;
        // TODO: Make sure that the file has 'games' and 'users'
        //       If not -> add them.
    }

    private function save($db_content)
    {

        $db_out = json_encode($db_content);
        $ret = file_put_contents($this->file, $db_out);

        if ($ret === false) {
            echo "ERROR: Could not write to database!!";
            return false;
        }

        return true;
    }

    private function load()
    {

        $db_raw = file_get_contents($this->file);
        $db_content = json_decode($db_raw, true);

        return $db_content;
    }

    public function addUser($id, $userName, $gameIds)
    {
        $db_content = $this->load();

        $newUser = [
            "id" => $id,
            "userName" => $userName,
            "gameIds" => $gameIds,
        ];

        array_push($db_content['users'], $newUser);

        $ret = $this->save($db_content);
        return $ret;
    }

    public function getBoardById($id)
    {

    }

    public function addGame($id, $player1Id, $player2Id, $board)
    {

        $db_content = $this->load();

        $newGame = [
            "id" => $id,
            "player1Id" => $player1Id,
            "player2Id" => $player2Id,
            "board" => json_encode($board),
        ];

        array_push($db_content['games'], $newGame);

        return $this->save($db_content);

    }

    // public function saveBoard($gameId, $board)
    // {
    //     // Load the current db
    //     $db_content = file_get_contents($this->file);
    //     $db = json_decode($db_content);
    //     // Save the board in the correct 'table'
    //     foreach ($db as $elem) {
    //         $found = false;
    //         foreach ($elem['games'] as $game) {
    //             if (is_numeric($game['id'])) {
    //                if (intval($game['id']) === $gameId) {
    //                    $found = true;
    //                    // TODO: Save to board!
    //                }
    //             }
    //         }
    //         if (!$found) {
    //             // Make new item
    //             array_push($game, {
    //                 id: uniqid();,
    //             })
    //         }
    //     }

    //     // Save the file again...
    //    $json = json_encode($board);
    //    file_put_contents($this->file, $json);

    //    return $boardId;

    // }

    public function loadBoard($boardId)
    {
       $raw_json = file_get_contents($this->file);
       $json = json_decode($raw_json);

    }


}