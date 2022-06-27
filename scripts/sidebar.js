/**
 * Open the side navbar.
 */
function openNav() {
    document.getElementById("mySidebar").style.width = "250px";
   $("#main").hide();
}

/**
 * Closes the side navbar.
 */
function closeNav() {
    document.getElementById("mySidebar").style.width = "0";
    $("#main").show();
}

/**
 * Gets the current player's color.
 */
function getColor() {
    let color = "";
    if ($('#red-pawns').css('visibility') === 'hidden') {
        color = "blue";
    } else if ($('#blue-pawns').css('visibility') === 'hidden') {
        color = "red";
    }
    return color;
}

/**
 * Gets the correct side of the board;
 * if less than 10 pieces are on a row, then the row is not returned.
 */
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

/**
 * Reverses a 2D array of any size (rotates 180 degrees);
 * intended for loading blue's setup.
 */
function fullReverse(arrayIn) {
    let arrayOut = []
    for (const i of arrayIn) {
        arrayOut.unshift(i.reverse());
    }
    return arrayOut
}

/**
 * Saves the user's current setup to a JSON file, in red format.
 */
function saveSetup() {
    const board = pieces_to_board();
    const color = getColor();
    const urlParams = new URLSearchParams(window.location.search);
    const userid = urlParams.get('userid');
    let setup = [];
    if (color === "red") {
        setup = getSide(7, board);
    } else if (color === "blue") {
        setup = fullReverse(getSide(1, board));
    }
    if(setup === false) {
        alert("Not all cells are filled in!");
    } else {
        const a = document.createElement("a");
        const file = new Blob([JSON.stringify(setup)], {type: "application/json"});
        a.href = URL.createObjectURL(file);
        a.download = userid.toString() + "-setup.json";
        a.click();
        a.remove();
    }
}

/**
 * Displays the setup loading block.
 */
function loadPrep() {
    let loadElement = $("#setup-load");
    if (loadElement.css("display") === "none") {
        loadElement.css("display", "block");
    } else {
        loadElement.css("display", "none");
    }
}
