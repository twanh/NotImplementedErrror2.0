/**
 * Gets the users information.
 *
 * @param  {} gameid The id of the current game.
 * @param  {} userid The id of the current user.
 * @returns {Promise<Object>} The user information (as promise).
 */
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
/**
 * Returns the user inforamtion of the user.
 *
 * Note: The userid and gameid are found in the url.
 *
 * @returns {Promise<Object>} The user information.
 */
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
/**
 * Checks if it is the current user's turn to make a move.
 *
 * @returns {Promise<boolean>} If it is the current user's turn.
 *
 */
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
/**
 * Get's the pieces of the current player.
 *
 * Note: the pieces of the other player are shown as "UNKNOWN".
 *
 * @returns {Promise<Array<Array<Object|string|null>>>} The board with the other players pieces redacted.
 *
 */
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

/**
 * Makes a move.
 *
 * @param {number} cur_y The current y position of the piece to move.
 * @param {number} cur_x The current x position of the piece to move.
 * @param {string} direction The direction ('up','down','left','right') to move the piece in.
 * @param {number} distance=1 The distance to move the piece with.
 * @returns {Promise<Object>} The return value from the server.
 */
function move(cur_y, cur_x, direction, distance = 1){

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
            distance,
        },
        dataType: 'json',
    })

    const ret = req.done((data) => {
        return data;
    });

    return ret;

}
