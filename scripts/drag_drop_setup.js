

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

function check_has_piece(event, classString, player_red_or_blue, pieceCount){
    let elementClasses = event.target.classList;
    let deleteClass = event.target.className.split(" ").slice(-1);
    let elementId = classString.split("-").slice(-1);
    
    if (elementClasses.length === 2) {
        piece = elementId;
        event.target.classList.remove(deleteClass)
        let deleteClassPiece = deleteClass[0].split("-").slice(-1);
        console.log(pieceCount[deleteClassPiece]);
        pieceCount[deleteClassPiece] += 1
        let last_char = document.getElementById(player_red_or_blue+"-"+deleteClassPiece+"-count").innerHTML.slice(-1);
        document.getElementById(player_red_or_blue+"-"+deleteClassPiece+"-count").innerHTML = pieceCount[deleteClassPiece]+"/"+last_char;
    } else if (elementClasses.length === 1){
        piece = elementId;
    }
    let last_char = document.getElementById(player_red_or_blue+"-"+piece+"-count").innerHTML.slice(-1);
    document.getElementById(player_red_or_blue+"-"+piece+"-count").innerHTML = pieceCount[piece]+"/"+last_char;
}



function removeDraggableAttributeById(element, player_red_or_blue, pieceCount) {
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


function removeDraggableAttributeByClass(element, classString, player_red_or_blue, pieceCount) {
    let piece = "";
    classString = classString.split(" ")[1];
    if (!isNaN(classString.charAt(4))) {
        piece = classString.charAt(4);
    } else {
        if (element.id.slice(4,4+3) === "spy") {
            piece = "spy";
        } else if (element.id.slice(4,4+4) === "bomb") {
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
    element.classList.remove(classString)
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
            const element = document.getElementById(classId);
            let classString = element.className;
            
            if (classId.startsWith("r-")) {
                removeDraggableAttributeByClass(element, classString, player_red_or_blue, pieceCount);
            } else {
                removeDraggableAttributeById(element, player_red_or_blue, pieceCount);
            }
            check_has_piece(event, classString, player_red_or_blue, pieceCount);
            console.log(classString)
            dropZone.classList.add("drop-zone", classString.split(" ").slice(-1));
            dropZone.draggable = true;
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

function piece_to_class(element) {
    const pieces = {
        "Marshal": "1",
        "General": "2",
        "Colonel": "3",
        "Major": "4",
        "Captain": "5",
        "Lieutenant": "6",
        "Sergeant": "7",
        "Miner": "8",
        "Scout": "9",
        "Spy": "spy",
        "Bomb": "bomb",
        "Flag": "flag",
        "": ""
    };
    return pieces[element]
}

function appendToBoard(jsonInst, rowstart) {
    let rowCounter = 0;
    let pieceCount = {
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
    let pieceInit = {
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
    let colour = ""
    if (rowstart === 1) {
        colour = "blue";
    } else if (rowstart === 7) {
        colour = "red";
    }
    for (let i = rowstart; i < 4 + rowstart; i++) {
        let boardRow = $("#row-"+i.toString()).children();
        let setupRow = jsonInst[rowCounter];
        let cellCounter = 0;
        for (let j = 0; j < 10; j++) {
            let piece = piece_to_class(setupRow[cellCounter]);
            if (piece !== "" && pieceCount[piece] !== 0) {
                pieceCount[piece] = pieceCount[piece] - 1;
                boardRow[cellCounter].className = "dropzone img-" + piece;
            } else {
                boardRow[cellCounter].className = "dropzone";
            }
            cellCounter += 1;
            if (pieceCount[piece] === 0) {
                $("#"+colour+"-"+piece+"-img").attr("draggable", "false");
            }
            $("#"+colour+"-"+piece+"-count").text(pieceCount[piece]+"/"+pieceInit[piece]);
        }
        rowCounter += 1;
    }
    return pieceCount;
}

function loadSetup() {
    const setupIn = JSON.parse($("#setupStr").val());
    $("#setup-load").css("display", "none");
    const color = getColor();
    let pieceCount = [];
    if (color === "red") {
        pieceCount = appendToBoard(setupIn, 7);
    } else if (color === "blue") {
        pieceCount = appendToBoard(fullReverse(setupIn), 1);
    }
    console.log("Load OK");
    closeNav();
    return pieceCount;
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

function checkBothReady(intv) {

    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);
    const gameid = urlParams.get('gameid');
    const userid = urlParams.get('userid');

    let request = $.ajax({
        url: "api/are_ready.php?gameid="+gameid,
        method: "GET",
    });

    request.done((data) => {
        console.log(data);
        if (data['success']) {
            console.log(data['message']);
            if (data['ready']) {
                clearInterval(intv);
                console.log("redirecting to setup now")
                window.location.replace('play_game.php?gameid=' + gameid + '&userid=' + userid);
            }
        } else {
            console.log(data['message']);
        }
    });
}

async function eventReady(pieceCount){

    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);
    const gameid = urlParams.get('gameid');
    const userid = urlParams.get('userid');

    const [ready, board] = check_ready(pieceCount);
    if (ready) {
        const data = await apiSetup(board);
        if (data['success']) {
            if (data['ready']) {
                console.log("Both players are ready!");
                window.location.replace('play_game.php?gameid=' + gameid + '&userid=' + userid);
            }
            console.log("Waiting for other player to be done!")
            const readyInterval = setInterval(() => {
                checkBothReady(readyInterval);
            },2000)
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
    let change_board = false;

    let pieceCount = player_red_or_blue === "red" ? pieceCountRed : pieceCountBlue;
    if (change_board===false) {
        change_board_for_player(player_red_or_blue);
    }
    document.addEventListener("dragstart", event => {
        event.dataTransfer.setData("text/plain", event.target.id);
        dragstart()
    })
    drag_drop(player_red_or_blue, pieceCount);

    $("#setup-submit").click(function() {
        console.log($("#setupStr").val())
        pieceCount = loadSetup();
    })

    $("#ready_button").click(function() {
        eventReady(pieceCount);
    })
    
    
}
setup_game();
