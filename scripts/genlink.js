function generateLink() {
    let newUrl = window.location.href.replace("game.php", "join.php").split("&")[0];
    $("#urlShare").append("<p>" + newUrl + "</p><p>Select and copy this link.</p>");
}

$(function(){
    $("#submit").click(function() {
        generateLink();
    })
})