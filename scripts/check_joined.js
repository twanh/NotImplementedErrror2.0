
function checkJoined(intv) {

    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);
    const gameid = urlParams.get('gameid');
    const userid = urlParams.get('userid');

    let request = $.ajax({
        url: "api/has_joined.php?gameid="+gameid,
        method: "GET",
    });

    request.done((data) => {
        console.log(data);
        if (data['success']) {
            console.log(data['message']);
            clearInterval(intv);
            console.log("redirecting to setup now")
            window.location.replace('setup.php?gameid=' + gameid + '&userid=' + userid);
        } else {
            console.log(data['message']);
        }
    });
}

const intv = setInterval(() => {
  checkJoined(intv);
}, 2000);
