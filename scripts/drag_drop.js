
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
}

function board_player_blue() {
    for (const red_id of document.querySelectorAll("td[id^='red-'][id$='-img']")) {
        red_id.draggable = false;
    }
}

if (player_red_or_blue === "red") {
    board_player_red()
} else if (player_red_or_blue === "blue") {
    board_player_blue()
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
        removeDraggableAttribute(event.target);
        event.dataTransfer.setData("text/plain", event.target.className);
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

        const className  = event.dataTransfer.getData('text/plain');
        
        dropZone.classList.add(className);
    });
}