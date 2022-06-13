function validateName(name) {
    let textElement = name.val()
    name.toggleClass("is-valid", textElement.length !== 0);
    name.toggleClass("is-invalid", textElement.length === 0);
    /* name.toggleClass("is-valid", !(/^((?!(<script>|<\?php)).)*$/g.test(textElement)));
    name.toggleClass("is-invalid", /^((?!(<script>|<\?php)).)*$/g.test(textElement)); */
    /* iets gaat hierboven mis */
    if($(".is-invalid").length === 0) {
        return true;
    } else {
        return false;
    }
}

function initGame(input) {
    if (validateName($("#name")) === true && $('input[name="color"]:checked').val() !== undefined) {
        console.log(input)
        /*let request = $.ajax({
            type: 'post',
            url: 'api/start_game.php',
            data: input,
            dataType: "json"
        });
        request.done(function (data) {
            let url = "game.php?gameid=" + data.gameid + "&userid=" + data.userid;
            window.location.replace(url);
        });*/
        $("#usernameForm").submit()
    }
}

$(function(){
    $("#submit").click(function() {
        initGame($("#usernameForm").serialize());
    })
})