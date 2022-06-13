setTimeout(function() {
    var xhr = new XMLHttpRequest;
    xhr.open('GET', '../api/has_joined.php', true);
    xhr.send('search=arduino');
}, 2000);