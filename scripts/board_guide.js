function highlightSide(rowstart, color) {
    for (let i = rowstart; i < 4 + rowstart; i++) {
        let rows = $("#row-"+i.toString()).children();
        console.log(rows)
        rows.css("background-color", color);
    }
}

async function tailorToPlayer() {
    console.log("Function tailorToPlayer active");
    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);
    const gameid = urlParams.get('gameid');
    const userid = urlParams.get('userid');
    const info = await getUserInfo(gameid, userid);
    const color = info["color"];
    console.log(color);
    if (color === "red") {
        highlightSide(7, "rgba(255, 153, 153, 0.5)");
    } else if (color === "blue") {
        highlightSide(1, "rgba(153, 153, 255, 0.5)");
    }
}

$(tailorToPlayer());