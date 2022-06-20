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
    "flag": 1,
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
    "flag": 1,
}

let player_red_or_blue = "blue";//get player color
const pieceCount = player_red_or_blue === "red" ? pieceCountRed : pieceCountBlue;

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

if (player_red_or_blue === "red") {
    board_player_red()
} else if (player_red_or_blue === "blue") {
    board_player_blue()
}


function check_has_piece(event, className){
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



function removeDraggableAttribute(element) {
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

for (const draggableElement of document.querySelectorAll("[draggable=true]")) {
    draggableElement.addEventListener("dragstart", event=> {
        event.dataTransfer.setData("text/plain", event.target.id);
    });
}

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
        removeDraggableAttribute(document.getElementById(classId));
        check_has_piece(event, className);
        dropZone.classList.add("drop-zone", className);
    });
}

function check_ready() {
    list_bool = [];
    for (const [key, entries] of  Object.entries(pieceCount)) {
        if (entries===0) {
            list_bool.push(true);
        } else {
            list_bool.push(false);
        }
    }
    if (list_bool.every(element => element === true)) {
        console.log("Ready!");
    } else {
        console.log("Not Ready!");
    }
}
setInterval(check_ready, 5000);