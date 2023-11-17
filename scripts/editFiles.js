const fileNameItem = document.body.querySelector("#edit_name");
const fileDescriptionItem =
    document.body.querySelector("#edit_description");
const fileIdItem = document.body.querySelector("#edit_id");
const fileCategoryIdItem = document.body.querySelector("#edit_category_id");
const editDialogId = document.body.querySelector("#edit");

function displayUpdateModal( id, name, description, category_id) {
    fileNameItem.value = name;
    fileDescriptionItem.textContent = description;
    fileIdItem.value = id;
    fileCategoryIdItem.value = category_id;

    editDialogId.showModal();
}

const pageNumberElement = document.body.querySelector("#page_number");

function submitCatcher(change) {
    pageNumberElement.value = String(Number(pageNumberElement.value) + change);
}
