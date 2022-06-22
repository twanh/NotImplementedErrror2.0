
function play(){

    // TODO: Load the board

    // Check if it's the current players turn (every 2s)
    const turnTimeout = setTimeout(() => {
        const data = checkIsTurn().then((isTurn) => {
            if (isTurn){
                console.log("It is your turn!")
                $('#move_btn').show();
            } else {
                console.log("It is not your turn!");
                $("#move_btn").hide();
            }
        })
    }, 2000)
    // Let the player make a move
}