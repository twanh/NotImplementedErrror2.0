
function checkJoined(intv) {

    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);
    const gameid = urlParams.get('gameid');

    let request = $.ajax({
        url: "api/has_joined.php?gameid="+gameid,
        method: "GET",
    });

    request.done((data) => {
        console.log(data);
        if (data['success']) {
            console.log(data['message']);
            clearInterval(intv);
            // TODO: Redirect to the actual game playing page.
            window.location.replace('test_board.php');
        } else {
            console.log(data['message']);
        }
    });
}

const intv = setInterval(() => {
  checkJoined(intv);
}, 2000);
