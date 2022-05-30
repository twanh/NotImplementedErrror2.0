# Stratego 

# GRID

10x10

  0 1 2 3 4 5 6 7 8 9
0
1
2
3
4
5
6
7
8
9

```json
{
  "id": '1231231',
  board: [
    [{player: 1, pawn: "bomb"}, ...],
    ...
    [{player: 2, pawn: "flag"}, ...],
  ],
  "player1Name": "",
  "player2Name": "",
}
```

# API

index.html -request->  ...

- `start_game.php`
  - makes id
  - return id to user
- `join_game.php`
  - when user uses join link
  - lets the players start their setup
- `setup_done.php`
  - validates setup
  - lets the platers start the game
- `move.php`
  - validates move
  - etc...
