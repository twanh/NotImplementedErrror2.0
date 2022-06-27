/**
 * Checks wether both players have joined.
 * If both players have joined the user is redirected to the setup page.
 *
 * @param {number} intv The number that references the interval used to call this function
 *                      (this is used to clear the interval when both players have joined).
 */
function checkJoined(intv) {
    // sends and api request if the other players has joined

    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);
    const gameid = urlParams.get('gameid');
    const userid = urlParams.get('userid');

    let request = $.ajax({
        url: "api/has_joined.php?gameid="+gameid,
        method: "GET",
    });

    request.done((data) => {
        if (data['success']) {
            clearInterval(intv);
            window.location.replace('setup.php?gameid=' + gameid + '&userid=' + userid);
        }
    });
}

$(function() {
    const intv = setInterval(() => {
      checkJoined(intv);
    }, 2000);
})
