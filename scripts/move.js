
function moveUp() {

    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);
    const gameid = urlParams.get('gameid');

    let cur_x = ""; // How do we get this data?
    let cur_y = ""; // How do we get this data?

    let request = $.ajax({
        url: "api/move/up.php",
        method: "POST",
        data: {
            gameid: gameid,
            cur_x: cur_x,
            cur_y: cur_y,
        },
        dataType: "json"
    });

    request.done((data) => {
        console.log(data);
    });
}

function moveDown() {

    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);
    const gameid = urlParams.get('gameid');

    let cur_x = ""; // How do we get this data?
    let cur_y = ""; // How do we get this data?

    let request = $.ajax({
        url: "api/move/down.php",
        method: "POST",
        data: {
            gameid: gameid,
            cur_x: cur_x,
            cur_y: cur_y,
        },
        dataType: "json"
    });

    request.done((data) => {
        console.log(data);
    });
}

function moveLeft() {

    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);
    const gameid = urlParams.get('gameid');

    let cur_x = ""; // How do we get this data?
    let cur_y = ""; // How do we get this data?

    let request = $.ajax({
        url: "api/move/left.php",
        method: "POST",
        data: {
            gameid: gameid,
            cur_x: cur_x,
            cur_y: cur_y,
        },
        dataType: "json"
    });

    request.done((data) => {
        console.log(data);
    });
}

function moveRight() {

    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);
    const gameid = urlParams.get('gameid');

    let cur_x = ""; // How do we get this data?
    let cur_y = ""; // How do we get this data?

    let request = $.ajax({
        url: "api/move/right.php",
        method: "POST",
        data: {
            gameid: gameid,
            cur_x: cur_x,
            cur_y: cur_y,
        },
        dataType: "json"
    });

    request.done((data) => {
        console.log(data);
    });
}

// TODO: if player makes move eventhandler then checkMove to send it to move.php
function eventMovePiece() {
    moveUp();
    moveDown();
    moveLeft();
    moveRight();
}