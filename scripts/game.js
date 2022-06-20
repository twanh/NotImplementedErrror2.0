
async function getUserInfo(gameid, userid) {

    let req = $.ajax({
        url: 'api/player.php',
        method: "POST",
        data: {
            gameid,
            userid,
        },
        dataType: 'json',
    });

    const data = await req.done((data) => {
        return data;
    });

    return data;
}

function getCurrentUserInfo() {

    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);
    const gameid = urlParams.get('gameid');
    const userid = urlParams.get('userid');

    const userinfo = getUserInfo(gameid, userid).then((data) => {
        return data;
    });

    return userinfo;

}

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
        },
        dataType: 'json',
    })

    req.done((data) => {
        console.log(data['board']);
    });


}
