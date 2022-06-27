/**
 * Generates an invite link
 */
function generateLink() {
    let newUrl = window.location.href.replace("lobby.php", "join.php").split("&")[0];
    $("#urlShare").append('<div class="input-group mb-4"><input type="text" class="form-control" id="myLink" value="' + newUrl + '" readonly><div class="input-group-append"><button onclick="copyLink()" class="btn btn-primary">Copy</button></div></div>');
}
/**
 * Makes the button copy the link to clipboard
 */
function copyLink() {
    var copyText = document.getElementById("myLink");
    copyText.select();
    navigator.clipboard.writeText(copyText.value);
}

$(function(){
    $("#submit").click(function() {
        generateLink();
    })
})