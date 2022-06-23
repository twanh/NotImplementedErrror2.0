let loadLoaded = false;

function openNav() {
    document.getElementById("mySidebar").style.width = "250px";
   $("#main").hide();
}
  
function closeNav() {
    document.getElementById("mySidebar").style.width = "0";
    $("#main").show();
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

async function saveSetup() {
    const board = pieces_to_board();
    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);
    const gameid = urlParams.get('gameid');
    const userid = urlParams.get('userid');
    const info = await getUserInfo(gameid, userid);
    const color = info["color"];
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
    if (!loadLoaded) {
        let loadButton = $('#load');
        loadButton.after('</form>');
        loadButton.after('<div class="btn btn-success btn-block" id="setup-submit" onclick="loadSetup()">Load</div>');
        loadButton.after('<textarea class="form-control" id="setupStr"></textarea>');
        loadButton.after('<label for="setupStr" class="form-label">Paste your setup here:</label>');
        loadButton.after('<form id="setup-load">');
        loadLoaded = true;
    }
}

async function loadSetup() {
    /* const setupIn = $("#setupStr").val();
    const setupJSON = JSON.parse(setupIn);
    loadLoaded = false;
    $("form #setup-load").remove();
    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);
    const gameid = urlParams.get('gameid');
    const userid = urlParams.get('userid');
    const info = await getUserInfo(gameid, userid);
    const color = info["color"];
    console.log(setupJSON);
    console.log(color); */
    //TODO: fix loadSetup because i can't be bothered to right now
    //      using CLIENT-SIDE color detection, not server-side
    //      since that seems to reset the board every time
    console.log("Load OK");
    closeNav();
}