
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

async function checkIsTurn() {

    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);
    const gameid = urlParams.get('gameid');
    const userid = urlParams.get('userid');

    let req = $.ajax({
        url: 'api/is_turn.php?gameid=' + gameid + '&playerid=' + userid,
        method: "GET",
    })

    const reqData = await req.done((data) => {
        return data;
    });

    let turn = false;
    if (reqData['success'])  {
        turn = reqData['turn'];
    }

    return turn;


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
    const board = req.done((data) => {
        return data;
    });

    return board;


}


// TODO: Add distance!
function move(cur_y, cur_x, direction){

    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);
    const gameid = urlParams.get('gameid');
    const userid = urlParams.get('userid');

    let req = $.ajax({
        url: 'api/move.php',
        method: "POST",
        data: {
            gameid,
            userid,
            direction,
            cur_x,
            cur_y,
        },
        dataType: 'json',
    })

    const ret = req.done((data) => {
        console.log(data);
        return data;
    });

    return ret;

}
