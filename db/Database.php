<?php

include_once __DIR__ . '/../classes/Board.php';

class Database
{
    /**
     * @var string The path of where the file used as database is stored.
     */
    public $file;


    /**
     * Create database for loading and saving of information.
     *
     * @param $file string The path of where the file used as database is stored.
     */
    public function __construct($file)
    {
        $this->file = $file;
    }

    /**
     * Save the given database content to the database file.
     * @param $db_content array The content to save to the database.
     * @return bool If the save as successful.
     */
    private function save($db_content): bool
    {

        if (is_null($db_content['games'])){
            return false;
        }

        $db_out = json_encode($db_content);
        $ret = file_put_contents($this->file, $db_out);

        if ($ret === false) {
            echo "ERROR: Could not write to database!!";
            return false;
        }

        return true;
    }

    /**
     * Load the content of the database.
     * @return array The loaded content of the database.
     */
    private function load()
    {

        $db_raw = file_get_contents($this->file);
        $db_content = json_decode($db_raw, true);

        return $db_content;
    }

    /**
     * Add a (new) user to the database.
     * @param $id string The (unique) user id.
     * @param $userName string The username of the user.
     * @param $gameIds string[] The games the user belongs to.
     * @return bool If the user was added to the database successfully.
     */
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

    /**
     * Returns the user with the given id.
     *
     * Example of a user array (as stored in the db):
     * ```json
     *  {
     *      "userName": "User1",
     *      "id": "1234234",
     *      "gameIds": ["game-123"]
     *  }
     * ```
     * So the keys `userName`, `id` and `gameIds` are available on the returned
     * array.
     *
     * @param string $id The user id.
     * @returns array|null The user array or NULL when not found.
     */
    public function getUserById($id)
    {
        $db_content = $this->load();
        $users = $db_content['users'];

        foreach ($users as $user) {
            if ($user['id'] == $id ) {
                return $user;
            }
        }

        return NULL;
    }

    /**
     * Returns the game with the given id from the database.
     *
     * Note: the game here is just the game array as it is stored in the database
     * the board and pieces are also just arrays, not their corresponding classes.
     *
     * An example of how the game is stored (in the database):
     * ```json
     * {
     *      "id":"62b8c48f78ac7",
     *      "player1Id":"62b8c48f78acd",
     *      "player2Id":"62b8c497a578f",
     *      "board":[...],
     *      "player1Ready":true,
     *      "player2Ready":true,
     *      "turn":1,
     *      "lastHitBy":null,
     *      "lastHitPiece":null
     * },
     * ```
     *
     * Note that not all these 'fields' are always accessible. E.g.: `turn` and `lastHitBy` are only added after
     * both players are ready. So it is always recommended to use the specialized methods
     * (e.g.: `getBoard`, `getLastHitForGame`, etc) to get these values if needed.
     *
     * @param $id string The id of the game.
     * @return array|null The game data -- NULL if no game is found.
     */
    public function getGameById($id)
    {
        $db_content = $this->load();
        foreach ($db_content['games'] as $game) {
            if ($game['id'] == $id) {
                return $game;
            }
        }

        return NULL;

    }

    /**
     * Returns the board that belongs to the given game(id).
     *
     * Note: here the board is an instance of the board class and also
     * all pieces are instances of their correct Piece (sub)class.
     * So all methods and instance variables can be used.
     *
     * @param $gameId string The id of the game to get the board from.
     * @return \board\Board The board (instance) that belongs to the given game.
     */
    public function getBoard($gameId) : \board\Board
    {
        $game = $this->getGameById($gameId);
        $board = $game['board'];
        return \board\Board::fromJson($board);
    }

    /**
     * Adds a game with the given data to the database.
     *
     * @param $id string The (unique) id for the game.
     * @param $player1Id string The player id for the red player.
     * @param $player2Id string The player id for the blue player.
     * @param $board \board\Board The board for the game.
     * @return bool If the game was saved successfully to the database.
     */
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

    /**
     * Updates the game with the given id.
     *
     * Only the parameters that are given are updated, so if
     * a field does not have to be update make it NULL.
     * E.g.: `updateGame('id', NULL, 'p2id', NULL)` would only update the
     * id for the second (blue) player.
     *
     * @param $id string The id of the game to update.
     * @param $player1Id string|null The id of the first (red) player.
     * @param $player2Id string|null The if the of the second (blue) player.
     * @param $board string|\board\Board|null The board that is used for the game.
     * @return bool If the game was updated successfully in the database.
     */
    public function updateGame($id, $player1Id = NULL, $player2Id = NULL, $board = NULL)
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
            if (is_subclass_of($board, 'Board')) {
                $gameToUpdate['board'] = $board->getBoard();
            } else {
                $gameToUpdate['board'] = $board;
            }
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

    /**
     * Sets the player with the given $playerid to ready for the game with the given $gameid.
     * @param $gameid string The id of the game to set the player ready for.
     * @param $playerid string The id of the player that is ready.
     * @return bool If the database was updated successfully.
     */
    public function setReadyForGame($gameid, $playerid)
    {

        $db_content = $this->load();

        foreach ($db_content['games'] as &$game) {
            if ($game['id'] == $gameid) {
                if ($playerid === $game['player1Id']) {
                    $game['player1Ready'] = true;
                } else {
                    $game['player2Ready'] = true;
                }
            }
        }
        unset($game);

        return $this->save($db_content);
    }


    /**
     * Returns if both players are ready for the game with the given $gameid.
     *
     * @param $gameid string The id of the game to check for if the players are ready.
     * @return bool|null If the players are ready, or null when the game is not found.
     */
    public function getReadyForGame($gameid)
    {

        $currentGame = $this->getGameById($gameid);
        if (is_null($currentGame)) {
            return NULL;
        }

        $p1Ready = false;
        $p2Ready = false;
        if (array_key_exists('player1Ready', $currentGame)) {
            $p1Ready = $currentGame['player1Ready'];
        }

        if (array_key_exists('player2Ready', $currentGame)) {
            $p2Ready = $currentGame['player2Ready'];
        }

        return $p1Ready && $p2Ready;
    }


    /**
     * Save what piece did the last hit and which player moved that piece.
     *
     * @param $gameid string The game for which the hit occurred
     * @param $userid string The user who moved the hitting piece.
     * @param $pieceName string The name of the piece that made the hit.
     * @return bool If the database could be updated successfully.
     */
    public function setLastHitForGame($gameid, $userid, $pieceName) {

        $db_content = $this->load();

        foreach ($db_content['games'] as &$game) {
            if ($game['id'] == $gameid) {
                $game['lastHitBy'] = $userid;
                $game['lastHitPiece'] = $pieceName;
            }
        }
        unset($game);

        return $this->save($db_content);

    }

    /**
     * Returns which player hit which piece last.
     * @param $gameid string The id of the game to get the last hit for
     * @return array Who hit the piece and what piece did the move.
     */
    public function getLastHitForGame($gameid) {

        $game = $this->getGameById($gameid);

        $hitBy = null;
        if (array_key_exists('lastHitBy', $game)) {
            $hitBy = $game['lastHitBy'];
        }

        $piece = NULL;
        if (array_key_exists('lastHitPiece', $game)) {
            $piece = $game['lastHitPiece'];
        }


        return [$hitBy, $piece];

    }


    public function setWinner($gameid, $playerid)
    {

        $db_content = $this->load();

        foreach ($db_content['games'] as &$game) {
            if ($game['id'] == $gameid) {
                $game['winner'] = $playerid;
            }
        }
        unset($game);

        return $this->save($db_content);
    }

    public function getWinner($gameid)
    {
        $game = $this->getGameById($gameid);

        if (array_key_exists('winner', $game)) {
            return $game['winner'];
        }

        return NULL;


    }

}
