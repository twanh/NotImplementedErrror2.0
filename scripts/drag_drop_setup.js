

function board_player_red() {
    for (const blue_id of document.querySelectorAll("td[id^='blue-'][id$='-img']")) {
        blue_id.draggable = false;
    }
    for(const dropzone of document.querySelectorAll("tr:nth-child(-n+6) .drop-zone")) {
        dropzone.classList.remove("drop-zone");
    }
}

function board_player_blue() {
    for (const red_id of document.querySelectorAll("td[id^='red-'][id$='-img']")) {
        red_id.draggable = false;
    }
    for(const dropzone of document.querySelectorAll("tr:nth-child(n+5) .drop-zone")) {
        dropzone.classList.remove("drop-zone");
    }
}

function change_board_for_player(player_red_or_blue) {
    if (player_red_or_blue === "red") {
        board_player_red()
    } else if (player_red_or_blue === "blue") {
        board_player_blue()
    }
}

function check_has_piece(event, className, player_red_or_blue, pieceCount){
    let element_classes = event.target.classList;
    let piece = undefined;
    if (element_classes.length == 2) {
        piece = element_classes.item(1).split('-')[1];
        element_classes.remove(element_classes.item(1));
        pieceCount[piece] += 1;
    } else {
        piece = className.split('-')[1];
        event.target.className = "";
    }
    let last_char = document.getElementById(player_red_or_blue+"-"+piece+"-count").innerHTML.slice(-1);
    document.getElementById(player_red_or_blue+"-"+piece+"-count").innerHTML = pieceCount[piece]+"/"+last_char;
}



function removeDraggableAttribute(element, player_red_or_blue, pieceCount) {
    let piece = "";
    number = player_red_or_blue === "red" ? 4 : 5;
    
    if (!isNaN(element.id.charAt(number))) {
        piece = element.id.charAt(number);
    } else {
        if (element.id.slice(number,number+3) === "spy") {
            piece = "spy";
        } else if (element.id.slice(number,number+4) === "bomb") {
            piece = "bomb";
        } else {
            piece = "flag";
        }
    }
    if (pieceCount[piece] <= 0) {
        element.draggable = false;
    } else {
        pieceCount[piece] = pieceCount[piece]-1;
        if (pieceCount[piece] <= 0) {
            element.draggable = false;
        } else {
            element.draggable = true;
        }
    }
    let last_char = document.getElementById(player_red_or_blue+"-"+piece+"-count").innerHTML.slice(-1);
    document.getElementById(player_red_or_blue+"-"+piece+"-count").innerHTML = pieceCount[piece]+"/"+last_char;
}

function dragstart(){
    for (const draggableElement of document.querySelectorAll("[draggable=true]")) {
        draggableElement.addEventListener("dragstart", event=> {
            event.dataTransfer.setData("text/plain", event.target.id);
        });
    }
}

function drag_drop(player_red_or_blue, pieceCount){
    for (const dropZone of document.querySelectorAll(".drop-zone")) {
        // When draggable element is over a dropzone
        dropZone.addEventListener("dragover", event => {
            event.preventDefault();
        });

        // When a draggable element is dropped onto a drop zone
        dropZone.addEventListener("drop", event => {
            event.preventDefault();

            const classId = event.dataTransfer.getData('text/plain');
            const className = document.getElementById(classId).className;
            dropZone.draggable="true";
            removeDraggableAttribute(document.getElementById(classId), player_red_or_blue, pieceCount);
            check_has_piece(event, className, player_red_or_blue, pieceCount);
            dropZone.classList.add("drop-zone", className);
        });
    }
}



function class_to_piece(element) {
    classPiece = element.classList[1];
    pieces = {
                "img-1": "Marshal",
                "img-2": "General",
                "img-3": "Colonel",
                "img-4": "Major",
                "img-5": "Captain",
                "img-6": "Lieutenant",
                "img-7": "Sergeant",
                "img-8": "Miner",
                "img-9": "Scout",
                "img-spy": "Spy",
                "img-bomb": "Bomb",
                "img-flag": "Flag",
                "": ""
            };
    return pieces[classPiece]
}

function pieces_to_board() {
    board = [
        [],
        [],
        [],
        [],
        [],
        [],
        [],
        [],
        [],
        []
    ];
    row_counter = 0
    const table = document.getElementById("board"); 
    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);
    for (const row of table.rows) {  
        for (const cell of row.cells) {
            piece = class_to_piece(cell);
            if (piece === undefined) {
                continue;
            } else {
                board[row_counter].push({"piece": piece, "player": urlParams.get('userid')});
            }
        }  
        row_counter += 1
    }
    console.log(board)
    return board
}



function check_ready(pieceCount) {
    list_bool = [];
    for (const entries in pieceCount) {
        if (pieceCount[entries] === 0) {
            list_bool.push(true);
        } else {
            list_bool.push(false);
        }
    }
    console.log(list_bool)
    console.log((list_bool.every(element => element === true)))
    if (list_bool.every(element => element === true)) {
        console.log("Ready!");
        return [true, pieces_to_board()];
    } else {
        console.log("Not Ready!");
        return [false, []]
    }
}

function apiSetup(board) {

    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);
    const gameid = urlParams.get('gameid');
    const userid = urlParams.get('userid');

    let req = $.ajax({
        url: 'api/setup.php',
        method: "POST",
        data: {
            gameid,
            userid,
            board: JSON.stringify(board),
        },
        dataType: 'json',
    });

    const data = req.done((data) => {
        console.log(data);
        return data;
    });

    return data;
}

async function eventReady(pieceCount){
    const [ready, board] = check_ready(pieceCount);
    if (ready) {
        const data = await apiSetup(board);
        if (data['success']) {
            if (data['ready']) {
                // TODO: Redirect to game
                console.log("Both players are ready!");
            }
            // TODO: Start timeout and wait for ready
            console.log("Waiting for other player to be done!")
        } else {
            if (data['errors'].length > 0) {
                for (let i =0; i < data['errors'].length; i++){
                    alert(data['errors'][i]);
                }
            }
        }
    } else {
        alert("Please place all pieces on your part of the board!")
    }
}

async function setup_game(){
    let player_info =  await getCurrentUserInfo();
    let player_red_or_blue = player_info.color;
    let pieceCountRed = {
        "1": 1,
        "2": 1,
        "3": 2,
        "4": 3,
        "5": 4,
        "6": 4,
        "7": 4,
        "8": 5,
        "9": 8,
        "spy": 1,
        "bomb": 6,
        "flag": 1
    }
    
    let pieceCountBlue = {
        "1": 1,
        "2": 1,
        "3": 2,
        "4": 3,
        "5": 4,
        "6": 4,
        "7": 4,
        "8": 5,
        "9": 8,
        "spy": 1,
        "bomb": 6,
        "flag": 1
    }

    let board = undefined;

    const pieceCount = player_red_or_blue === "red" ? pieceCountRed : pieceCountBlue;
    change_board_for_player(player_red_or_blue);

    dragstart();
    drag_drop(player_red_or_blue, pieceCount);
    
    $("#ready_button").click(function() {
        eventReady(pieceCount);
    })
}
setup_game();
