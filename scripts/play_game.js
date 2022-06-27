/**
 * Returns the image class that corresponds to the name of the piece.
 *
 * @param  {string} pieceName Name of the piece.
 * @return {string} Image class used for that piece.
 */
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

/**
 * All pieces owned by the player are made draggable.
 */
function makeTableDraggable(){

    for (const cell of document.querySelectorAll("#board tr td")) {
        if (cell.className.split(" ").includes("img-unknown")) {
            cell.draggable = false;
        } else {
            cell.draggable = true;
        }
    }
    for (const cell of document.querySelectorAll("table[id$=-pawns] tr td")) {
        cell.draggable = false;
    }
}
/**
 * Sets up the moving of the pieces.
 *
 * All the players pieces are made draggable and event handlers are added for dragging and dropping.
 * When a drag/drop occurs `playerMadeMove` is called to handle the move.
 *
 * @param {Array<Array<Object|string|null>>} board The current board for the player.
 */
function setupMoving(board) {

    // For every draggable element make sure that when a drag
    // occurs the id of the current element is passed through.
    for (const draggableElement of document.querySelectorAll("[draggable=true]")) {
        draggableElement.addEventListener("dragstart", event=> {
            event.dataTransfer.setData("text/plain", event.target.id);
        });
    }

    // For everyplace where a piece can be dropped make sure
    // to place the piece there and handle the move.
    const dropzones = document.querySelectorAll('.drop-zone');
    for (const dz of dropzones) {
        dz.addEventListener('dragover', (e) => {
            e.preventDefault();
        })

        dz.addEventListener('drop', event => {
            event.preventDefault();
            event.stopImmediatePropagation();

            // Get the id of the place where the piece came from.
            const loc = event.dataTransfer.getData('text/plain');
            // Get the element to move.
            const element = document.getElementById(loc);
            const classString = element.className;
            // Remove the img from the current place
            // and make it a place where pieces can move to.
            element.className = 'drop-zone';
            element.draggable = false;

            // Make the new position show the piece and make it draggable.
            dz.classList.add("drop-zone", classString.split(" ").slice(-1));
            dz.draggable = true;

            playerMadeMove(loc, dz.id, board);

        })
    }
}
/**
 * Handles the it when a player made a move.
 *
 * @param {string} start The classname of the starting position (of the piece that moved).
    E.g: `r-1-c-1` -- this is the classname of the position in the board table.
 * @param  {string} end The classname of the position where the piece got dragged to.
    E.g.: `r-2-c-1` -- this is the classname of the position in the board table.
 * @param  {Array<Array<Object|string|null>>} board The board.
 */
async function playerMadeMove(start, end, board) {

    // The rows and columns in the board table are not 0-indexed but the board
    // array is so -1 is applied to all coordinates that come from the table classnames.
    const start_y = parseInt(start.split('-')[1])-1;
    const start_x = parseInt(start.split('-')[3])-1;

    const end_y = parseInt(end.split('-')[1])-1;
    const end_x = parseInt(end.split('-')[3])-1;

    // Prevent moving in the water
    // Note: water tiles are also not enabled as dropzones, so this should
    // not occur often.
    if (board[end_y][end_x] === "WATER") {
        alert("You cannot move in the water.")
        fillBoard(board);
    }

    // Determine if the player moved up/down/left/right
    // Up    end_y < start_y (start_x=end_x)
    // Down  end_y > start_y (start_x=end_x)
    // Left  end_x < start_x (start_y=end_y)
    // Right end_x > start_x (start_y=end_y)

    let ret;
    if (end_y < start_y) {
        if (start_x !== end_x) {
            alert("You cannot move vertical and horizontal at the same time!");
            fillBoard(board);
            return;
        }

        let distance = start_y - end_y;
        ret = await move(start_y, start_x, 'up', distance)

    } else if (end_y > start_y) {
        if (start_x !== end_x) {
            alert("You cannot move vertical and horizontal at the same time!");
            updateBoard(board);
            return;
        }

        let distance = end_y - start_y;
        ret = await move(start_y, start_x, 'down', distance)

    } else if (end_x < start_x) {
        if (start_y !== end_y) {
            alert("You cannot move vertical and horizontal at the same time!");
            updateBoard(board);
            return;
        }

        let distance = start_x - end_x;
        ret = await move(start_y, start_x, 'left', distance);
    } else if (end_x > start_x) {
        if (start_y !== end_y) {
            alert("You cannot move vertical and horizontal at the same time!");
            updateBoard(board);
            return;
        }

        let distance = end_x - start_x;
        ret = await move(start_y, start_x, 'right', distance);
    } else {
        alert("You performed an illigal move.")
        fillBoard(board);
    }

    if (ret.success) {
        // Update the pieces locations
        fillBoard(ret.board);
        if (ret.message.length > 1) {
            alert(ret.message)
        }

        if (ret.youWin) {
            window.location.replace("result.php?winner=true");
        }

    } else {
        // Undo the move if the move was not valid.
        alert(ret.message);
        if ('board' in ret) {
            // Use the board that was returned with the request.
            fillBoard(ret.board);
        } else {
            // Get the latest version (aka the version before the invalid move) and use
            // that to restore the board to a valid state.
            updateBoard();
        }
    }
}


/**
 * Updates the board according to the given board.
 *
 * @param {Array<Array<Object|string|null>>} board The array of arrays representing the board containing the pieces
 * and their location, empty tiles (null) and water tiles (string).
 */
function fillBoard(board) {

    for (let y = 0; y < 10; y++) {
        for (let x = 0; x < 10; x++) {
            let cur = board[y][x];

            // Note: the table rows and cols are not 0 indexed, but the
            // board (matrix) is, so for the (y,x) of the board (board[y][x])
            // correspond to (y+1, x+1) in the table.
            let tableRowColID = '#r-' + (y+1) + '-c-' + (x+1);

            $(tableRowColID).attr('class', '')

            // SKIP WATER
            if (cur === 'WATER') {
                continue;
            }
            // No need to place anything on empty squares
            if (cur === null) {
                $(tableRowColID).addClass('drop-zone');
                continue;
            }

            if (cur === "UNKNOWN") {
                // Shows the question mark for the pieces of the other players.
                $(tableRowColID).addClass('img-unknown');
                $(tableRowColID).addClass('drop-zone');
            } else {
                // Adds the correct icon for the piece to it's position on the board.
                $(tableRowColID).addClass(pieceToClass(cur.name));
                $(tableRowColID).attr('draggable', true);
            }
        }
    }

}

/**
 * Updates the board according to the database and the last move/hit.
 * Also notifies the player after being hit.
 */
function updateBoard() {

    const board = getPlayerPieces().then(data => {
        if (data['success']) {

            if (data['lastHit'] !== null) {
                alert("You got hit by " + data['lastHit']);
            }

            console.log(data);


            if ('isWinner' in data) {
                if (data['youWon']) {
                    window.location.replace('result.php?winner=true');
                } else {
                    window.location.replace('result.php?winner=false');
                }
            }

            return data['board'];
        }
    });

    // Every time the board is updated:
    // - Refill the board with the new positions
    // - Make sure that the new positions are draggable.
    // - Setup the event handling for making moves.
    board.then(board => {
        fillBoard(board);
        makeTableDraggable();
        setupMoving(board);
    });

}
/**
 * Main function that first sets the board and then handles turns and moves.
 */
function play(){

    updateBoard();

    // Check if it's the current players turn (every 1s)
    let wasTurn = false;
    setInterval(() => {
        checkIsTurn().then((isTurn) => {
            $('#turn-container').show();
            if (isTurn){
                if (!wasTurn) {
                    updateBoard();
                    wasTurn = true;
                }
                $('#your-turn').show();
                $('#their-turn').hide();
            } else {
                $("#your-turn").hide();
                $("#their-turn").show();
                wasTurn = false;
            }
        })
    }, 1000)
}
