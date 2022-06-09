<?php

class Database
{
    public $file;
    public $open; // To check if the file is currently opened.

    // Load for file, or perhaps id
    public function __construct($file)
    {
        $this->file = $file;
    }

    public function saveBoard($boardId, $board)
    {
       $json = json_encode($board);
       file_put_contents($this->file, $json);

       return $boardId;

    }

    public function loadBoard($boardId)
    {
       $raw_json = file_get_contents($this->file);
       $json = json_decode($raw_json);

    }


}