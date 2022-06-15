const draggableElement = document.querySelector(".draggableElement");

// When a drag event starts
draggableElement.addEventListener("dragstart", e=> {
    e.dataTransfer.setData("text/plain", draggableElement.id);
});

for (const dropZone of document.querySelectorAll(".drop-zone")) {
    // When draggable element is over a dropzone
    dropZone.addEventListener("dragover", e => {
        e.preventDefault();
    });

    // When a draggable element is dropped onto a drop zone
    dropZone.addEventListener("drop", e => {
        e.preventDefault();

        const droppedElementId = e.dataTransfer.getData("text/plain");
        const droppedElement = document.getElementById(droppedElementId);

        dropZone.appendChild(droppedElement);
    });
}