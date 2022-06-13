function validateName(name) {
    let textElement = name.val()
    name.toggleClass("is-valid", textElement.length !== 0);
    name.toggleClass("is-invalid", textElement.length === 0);
    /* name.toggleClass("is-valid", !(/^((?!(<script>|<\?php)).)*$/g.test(textElement)));
    name.toggleClass("is-invalid", /^((?!(<script>|<\?php)).)*$/g.test(textElement)); */
    /* iets gaat hierboven mis */
    let ret = textElement.length !== 0;
    console.log(ret);
    return ret;
}

function initGame(input) {
    if (validateName($("#name"))) {
        $("#usernameForm").submit()
    }
}

$(function(){
    $("#submit").click(function() {
        initGame($("#usernameForm").serialize());
    })
})