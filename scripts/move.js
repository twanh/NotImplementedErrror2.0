
function checkMove() {

    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);
    const gameid = urlParams.get('gameid');

    let move_from = ""; // How do we get this data?
    let move_to = ""; // How do we get this data?

    let request = $.ajax({
        url: "api/move.php?gameid="+gameid,
        method: "POST",
        data: {
            from: move_from,
            to: move_to,
        },
    });

    request.done((data) => {
        console.log(data);
    });
}

// TODO: if player makes move eventhandler then checkMove to send it to move.php
function eventMovePiece() {
    checkMove();
}