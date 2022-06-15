<?php

include __DIR__ . '../classes/Board.php';

class Database
{
    public $file;

    // Load for file, or perhaps id
    public function __construct($file)
    {
        $this->file = $file;

        $content  = $this->load();

        if (!is_array($content)) {
            $content = Array(
                "games" => [],
                "users" => [],
            );
        }

        if(!array_key_exists('games', $content)) {
            $content['games'] = [];
        }

        if(!array_key_exists('users', $content)) {
           $content['users'] = [];
        }

        $this->save($content);

    }

    private function save($db_content): bool
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

    public function getGameById($id)
    {
        // TODO: Make sure the the board and pieces are replaced
        //       by their actual class instances.
        $db_content = $this->load();
        foreach ($db_content['games'] as $game) {
            if ($game['id'] == $id) {
                return $game;
            }
        }

        return NULL;

    }

    public function getBoard($gameId)
    {
        $game = $this->getGameById($gameId);
        $board = $game['board'];
        return \board\Board::fromJson($board);
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

    public function updateGame($id, $player1Id, $player2Id, $board)
    {

        $db_content = $this->load();
        $gameToUpdate = NULL;

        foreach ($db_content['games'] as $game) {
            if ($game['id'] == $id) {
                $gameToUpdate = $game;
            }
        }

        if (is_null($gameToUpdate)) {
            echo "ERROR: Could not find game with id " . $id . "so could not update";
            return false;
        }

        if(!is_null($player1Id)) {
            $gameToUpdate['player1Id'] = $player1Id;
        }

        if(!is_null($player2Id)) {
            $gameToUpdate['player2Id'] = $player2Id;
        }

        if(!is_null($board)) {
            $gameToUpdate['board'] = $board;
        }

        foreach ($db_content['games'] as &$game) {
            if ($game['id'] == $id) {
                $game = $gameToUpdate;
            }
        }
        unset($game);

        return $this->save($db_content);

    }

    /**
     * Returns which players turn it is for the game with the given $gameid.
     *
     * If the turn has not been set yet in the database 1 (means red) is returned
     * since red always starts.
     *
     * @param $gameid string The game id for the game to get the current turn for.
     * @return int The player which turn it is (either 1 or 2)
     *             If the game with the given gameid is not found NULL is returned.
     */
    public function getTurnForGame($gameid)
    {
        $currentGame = $this->getGameById($gameid);
        if (is_null($currentGame)) {
            return NULL;
        }
        if (array_key_exists('turn', $currentGame)) {
            return $currentGame['turn'];
        }
        // Red start by default
        return 1;
    }

    /**
     * Updates the game with the given $gameid with the given turn.
     * @param $gameid string The game id of the game to update.
     * @param $turn int The player (1 or 2) that has the next turn.
     * @return bool If the update was successful.
     */
    public function setTurnForGame($gameid, $turn)
    {

        $db_content = $this->load();

        foreach ($db_content['games'] as &$game) {
            if ($game['id'] == $gameid) {
                $game['turn'] = $turn;
            }
        }
        unset($game);

        return $this->save($db_content);
    }


}