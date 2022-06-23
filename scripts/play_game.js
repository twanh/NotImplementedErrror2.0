
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

function make_table_draggable(){
    for (const cell of document.querySelectorAll("#board tr td")) {
        cell.draggable = true
    }
}

function setupMoving(board) {

    // For every draggable element make sure that when a drag
    // occurs the id of the current element is passed through.
    for (const draggableElement of document.querySelectorAll("[draggable=true]")) {
        console.log({draggableElement})
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

function updateDropZones() {
    
    const board = document.getElementById('board')
    const gridPos = board.getElementsByTagName('td')

    for (gd of gridPos) {
        console.log({gd})
        if (gd.className.includes('img-unkown')) {
            console.log('found unkown')
        }
    }
    

}

function playerMadeMove(start, end, board) {

    const start_y = parseInt(start.split('-')[1])-1;
    const start_x = parseInt(start.split('-')[3])-1;

    const end_y = parseInt(end.split('-')[1])-1;
    const end_x = parseInt(end.split('-')[3])-1;

    if (board[end_y][end_x] === "WATER") {
        // TODO: Auto place the piece back
        alert("You cannot move in the water.")
    }

    // Determine if the player moved up/down/left/right
    // Up end_y < start_y    (start_x=end_x)
    // Down end_y > start_y  (start_x=end_x)
    // Left end_x < start_x  (start_y=end_y)
    // Right end_x > start_x (start_y=end_y)

    if (end_y < start_y) {
        if (start_x !== end_x) {
            alert("You cannot move vertical and horizontal at the same time!");
        }
        // TODO: API CALL: Move up
        console.log("Moved UP!");
    } else if (end_y > start_y) {
        if (start_x !== end_x) {
            alert("You cannot move vertical and horizontal at the same time!");
        }
        // TODO:API CALL: Move down
        console.log("Moved down");
    } else if (end_x < start_x) {
        if (start_y !== end_y) {
            alert("You cannot move vertical and horizontal at the same time!");
        }
        // TODO:API CALL: Move left 
        console.log("Moved left");
    } else if (end_x > start_x) {
        if (start_y !== end_y) {
            alert("You cannot move vertical and horizontal at the same time!");
        }
        // TODO:API CALL: Move right 
        console.log("Moved right");
    } else {
        alert("You performed an illigal move.")
        // TODO: Undo the drag and drop!
    }



}

function fillBoard(board) {

    for (let y = 0; y < 10; y++) {
        for (let x = 0; x < 10; x++) {
            let cur = board[y][x];

            let tableRowColID = '#r-' + (y+1) + '-c-' + (x+1);

            // SKIP WATER
            if (cur === 'WATER') {
                continue;
            }
            // No need to place anything on empty squares
            if (cur === null) {
                console.log("ADding dz to: ", tableRowColID)
                $(tableRowColID).addClass('drop-zone');
                continue;
            }

            if (cur === "UNKNOWN") {
                $(tableRowColID).addClass('img-unknown');
                $(tableRowColID).addClass('drop-zone');
            } else {
                $(tableRowColID).addClass(pieceToClass(cur.name));
                $(tableRowColID).attr('draggable', true);
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
        setupMoving(board);
    })
    
    make_table_draggable();

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
