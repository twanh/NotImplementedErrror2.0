const draggableElement = document.querySelector(".draggableElement");

if (draggableElement) {
// When a drag event starts
draggableElement.addEventListener("dragstart", event=> {
    event.dataTransfer.setData("text/plain", draggableElement.id);
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

        const droppedElementId = event.dataTransfer.getData("text/plain");
        const droppedElement = document.getElementById(droppedElementId);

        dropZone.classList.add("img-1");
    });
}