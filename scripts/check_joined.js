
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

const intv = setInterval(() => {
  checkJoined(intv);
}, 2000);
