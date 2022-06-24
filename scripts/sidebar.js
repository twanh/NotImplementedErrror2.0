function openNav() {
    document.getElementById("mySidebar").style.width = "250px";
   $("#main").hide();
}
  
function closeNav() {
    document.getElementById("mySidebar").style.width = "0";
    $("#main").show();
}

function getColor() {
    let color = "";
    if ($('#red-pawns').css('visibility') === 'hidden') {
        color = "blue";
    } else if ($('#blue-pawns').css('visibility') === 'hidden') {
        color = "red";
    }
    // TODO: create more thorough check for colour
    return color;
}

function getSide(rowstart, board) {
    const rows = [
        [],
        [],
        [],
        []
    ];
    let row_counter = rowstart - 1;
    for (let i = rowstart; i < 4 + rowstart; i++) {
        let rowIn = board[row_counter];
        let rowOut = []
        if(rowIn.length === 10) {
            let cell_counter = 0;
            for (let j = 0; j < 10; j++) {
                if (rowIn[cell_counter].length !== 0) {
                    rowOut[cell_counter] = rowIn[cell_counter].piece;
                }
                cell_counter += 1;
            }
        }
        if(rowOut.length === 0) {
            return false;
        }
        rows[row_counter - rowstart + 1] = rowOut;
        row_counter += 1
    }
    return rows;
}

function fullReverse(arrayIn) {
    let arrayOut = []
    for (const i of arrayIn) {
        arrayOut.unshift(i.reverse());
    }
    return arrayOut
}

function piece_to_class(element) {
    const pieces = {
        "Marshal": "img-1",
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
        "": ""
    };
    return pieces[element]
}

function appendToBoard(jsonInst, rowstart) {
    let rowCounter = 0;
    for (let i = rowstart; i < 4 + rowstart; i++) {
        let boardRow = $("#row-"+i.toString()).children();
        let setupRow = jsonInst[rowCounter];
        let cellCounter = 0;
        console.log(boardRow);
        for (let j = 0; j < 10; j++) {
            let piece = piece_to_class(setupRow[cellCounter]);
            if (piece !== "") {
                boardRow[cellCounter].className = "dropzone " + piece;
            } else {
                boardRow[cellCounter].className = "dropzone";
            }
            cellCounter += 1;
        }
        rowCounter += 1;
    }
}

function saveSetup() {
    const board = pieces_to_board();
    const color = getColor();
    let setup = [];
    if (color === "red") {
        setup = getSide(7, board);
    } else if (color === "blue") {
        setup = fullReverse(getSide(1, board));
    }
    console.log(setup);
    if(setup === false) {
        console.log("Not all cells are filled in!") //TODO: make nicer error messages
    } else {
        const a = document.createElement("a");
        const file = new Blob([JSON.stringify(setup)], {type: "application/json"});
        a.href = URL.createObjectURL(file);
        a.download = userid.toString() + "-setup.json";
        a.click();
        a.remove();
        console.log("Save OK");
    }
}

function loadPrep() {
    let loadElement = $("#setup-load");
    if (loadElement.css("display") === "none") {
        loadElement.css("display", "block");
    } else {
        loadElement.css("display", "none");
    }
}

function loadSetup() {
    const setupIn = JSON.parse($("#setupStr").val());
    $("#setup-load").css("display", "none");
    const color = getColor();
    if (color === "red") {
        appendToBoard(setupIn, 7);
    } else if (color === "blue") {
        appendToBoard(fullReverse(setupIn), 1);
    }
    console.log("Load OK");
    closeNav();
}