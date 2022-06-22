
function pieceToClass(pieceName) {

    const pieces = {
        'Marshal': 'img-1',
        "General": "img-2",
        "Colonel": "img-3",
        "Major": "img-4",
        "Captain": "img-5",
        "Lieutenant": "img-6",
        "Sergeant": "img-7",
        "Miner": "img-8",
        "Scout": "img-9",
        "Spy": "img-spy",
        "Bomb": "img-bomb",
        "Flag": "img-flag",
    };

    return pieces[pieceName];
}

function fillBoard(board) {

    for (let y = 0; y < 10; y++) {
        for (let x = 0; x < 10; x++) {
            let cur = board[y][x];
            let tableRowColID = '#r-' + (y+1) + '-c-' + (x+1);
            // No need to place anything on empty squares
            if (cur === null || cur === "WATER") {
                continue;
            }

            if (cur === "UNKNOWN") {
                $(tableRowColID).addClass('img-unknown');
            } else {
                $(tableRowColID).addClass(pieceToClass(cur.name));
            }
        }
    }

}



function play(){

    // TODO: Load the board
    const board = getPlayerPieces().then(data => {
        if (data['success']) {
            return data['board'];
        }
    });

    board.then(board => {
        fillBoard(board);
    })

    // Check if it's the current players turn (every 2s)
    const turnTimeout = setTimeout(() => {
        const data = checkIsTurn().then((isTurn) => {
            if (isTurn){
                console.log("It is your turn!")
                $('#move_btn').show();
            } else {
                console.log("It is not your turn!");
                $("#move_btn").hide();
            }
        })
    }, 2000)
    // Let the player make a move
}