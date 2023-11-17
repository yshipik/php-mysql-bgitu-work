const pageNumberElement = document.body.querySelector("#page_number");

function submitCatcher(change) {
    pageNumberElement.value = String(Number(pageNumberElement.value) + change);
}