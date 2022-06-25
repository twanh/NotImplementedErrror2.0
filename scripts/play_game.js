
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

async function playerMadeMove(start, end, board) {

    const start_y = parseInt(start.split('-')[1])-1;
    const start_x = parseInt(start.split('-')[3])-1;

    const end_y = parseInt(end.split('-')[1])-1;
    const end_x = parseInt(end.split('-')[3])-1;

    if (board[end_y][end_x] === "WATER") {
        alert("You cannot move in the water.")
        fillBoard(board);
    }

    // Determine if the player moved up/down/left/right
    // Up end_y < start_y    (start_x=end_x)
    // Down end_y > start_y  (start_x=end_x)
    // Left end_x < start_x  (start_y=end_y)
    // Right end_x > start_x (start_y=end_y)
    
    // TODO: Check and calc distance!

    let ret;
    if (end_y < start_y) {
        if (start_x !== end_x) {
            alert("You cannot move vertical and horizontal at the same time!");
        }

        console.log("Moved UP!");
        ret = await move(start_y, start_x, 'up')

    } else if (end_y > start_y) {
        if (start_x !== end_x) {
            alert("You cannot move vertical and horizontal at the same time!");
        }

        console.log("Moved down");
        ret = await move(start_y, start_x, 'down')

    } else if (end_x < start_x) {
        if (start_y !== end_y) {
            alert("You cannot move vertical and horizontal at the same time!");
        }

        console.log("Moved left");
        ret = await move(start_y, start_x, 'left')

    } else if (end_x > start_x) {
        if (start_y !== end_y) {
            alert("You cannot move vertical and horizontal at the same time!");
        }

        console.log("Moved right");
        ret = await move(start_y, start_x, 'right')

    } else {
        alert("You performed an illigal move.")
        fillBoard(board);
    }

    if (ret.success) {
        // Update the pieces locations
        fillBoard(ret.board);
    } else {
        // Undo the move!
        console.log(ret);
        alert(ret.message);
        if ('board' in ret) {
            fillBoard(ret.board);
        } else {
            fillBoard(board);
        }
    }




}

function fillBoard(board) {

    for (let y = 0; y < 10; y++) {
        for (let x = 0; x < 10; x++) {
            let cur = board[y][x];

            let tableRowColID = '#r-' + (y+1) + '-c-' + (x+1);

            $(tableRowColID).attr('class', '')

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

    const board = getPlayerPieces().then(data => {
        if (data['success']) {
            return data['board'];
        }
    });

    board.then(board => {
        fillBoard(board);
        make_table_draggable();
        setupMoving(board);
    })

    

    // Check if it's the current players turn (every 1s)
    const turnTimeout = setInterval(() => {
        const data = checkIsTurn().then((isTurn) => {
            $('#turn-container').show();
            if (isTurn){
                $('#your-turn').show();
                $('#their-turn').hide();
            } else {
                $("#your-turn").hide();
                $("#their-turn").show();
            }
        })
    }, 1000)

    // Let the player make a move

}
