

for (const draggableElement of document.querySelectorAll("[draggable=true]")) {
    draggableElement.addEventListener("dragstart", event=> {
        event.dataTransfer.setData("text/plain", event.target.className);
        console.log(event.target.className);
    });
}

for (const dropZone of document.querySelectorAll(".drop-zone")) {
    

    // When draggable element is over a dropzone
    dropZone.addEventListener("dragover", event => {
        event.preventDefault();
    });

    // When a draggable element is dropped onto a drop zone
    dropZone.addEventListener("drop", event => {
        event.preventDefault();

        const className  = event.dataTransfer.getData('text/plain');
        const droppedElementId = event.target.className;
        console.log(droppedElementId);
        
        dropZone.classList.add(className);
    });
}