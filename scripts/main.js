function initGame() {
    $.post('index.php', $('#usernameForm').serialize());
}

$(function(){
    $("#name-submit").click(function() {
        initGame()
    })
})