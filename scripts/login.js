function initGame() {
    let request = $.ajax({
        type: 'post',
        url: 'api/start_game.php',
        data: $(this).serialize(),
        dataType: "json"
    });
    request.done(function(data) {
        let url = "game.php?gameid=" + data.gameid + "&userid=" + data.userid;
        window.location.replace("game.php?");
    });
}

$(function(){
    $("#usernameForm").submit(function(e) {
        e.preventDefault();
        initGame()
    })
})