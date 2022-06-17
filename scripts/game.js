


function getPlayerPieces() {

    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);
    const gameid = urlParams.get('gameid');
    const userid = urlParams.get('userid');


    let req = $.ajax({
        url: 'api/board.php',
        method: "POST",
        data: {
            gameid,
            userid,
        }
    })

    req.done((data) => {
        console.log(data['board']);
    });


}