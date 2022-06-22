function highlightSide(rowstart, color) {
    for (let i = rowstart; i < 4 + rowstart; i++) {
        let rows = $("#row-"+i.toString()).children();
        rows.css("background-color", color);
    }
    let inverse = (rowstart + 4) % 10;
    for (let i = inverse; i < 6 + inverse; i++) {
        let rows = $("#row-"+i.toString()).children();
        rows.css("background-color", "rgba(0, 0, 0, 0.5)");
    }
}

async function tailorToPlayer() {
    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);
    const gameid = urlParams.get('gameid');
    const userid = urlParams.get('userid');
    const info = await getUserInfo(gameid, userid);
    const color = info["color"];
    if (color === "red") {
        highlightSide(7, "rgba(255, 153, 153, 0.2)");
        $("#blue-pawns").css("visibility", "hidden");
    } else if (color === "blue") {
        highlightSide(1, "rgba(153, 153, 255, 0.2)");
        $("#red-pawns").css("visibility", "hidden");
    }
}

$(tailorToPlayer());