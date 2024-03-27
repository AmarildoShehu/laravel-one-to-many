const placeholder = "https://";
const imageField = document.getElementById("image");
const previewField = document.getElementById("preview");

// Prendiamo bottone e input-group
const changeImageButton = document.getElementById("change-image-button");
const previousImageField = document.getElementById("previous-image-field");
// Gestione preview img
let blobUrl;

imageField.addEventListener("change", () => {
    // Controllo se è stato selezionato un file
    if (imageField.files && imageField.files[0]) {
        // prendo il file
        const file = imageField.files[0];

        // Preparo l'URL
        blobUrl = URL.createObjectURL(file);

        // lo inserisco nell'src
        previewField.src = blobUrl;
    } else {
        previewField.src = imageField.value || placeholder;
    }
});

window.addEventListener("beforeunload", () => {
    if (blobUrl) URL.revokeObjectURL(blobUrl);
});

// Gestione campo file

// Al click del bottone cambio l'input
changeImageButton.addEventListener("click", () => {
    previousImageField.classList.add("d-none");
    imageField.classList.remove("d-none");
    previewField.src = placeholder;
    imageField.click();
});
