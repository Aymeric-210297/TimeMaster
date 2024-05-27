const flashMessage = document.getElementById("flash-message");
const closeFlashMessage = document.getElementById("close-flash-message");

if (flashMessage && closeFlashMessage) {
    closeFlashMessage.addEventListener("click", function () {
    document.body.removeChild(flashMessage);
    });
}
