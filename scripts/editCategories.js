

const categoryNameItem = document.body.querySelector('#edit_name');
const categoryDescriptionItem = document.body.querySelector('#edit_description');
const categoryIdItem = document.body.querySelector('#edit_id');
const editDialogId = document.body.querySelector('#edit');

function displayUpdateModel(id, name, description) {
    categoryNameItem.value = name;
    categoryDescriptionItem.textContent = description;
    categoryIdItem.value = id;
    editDialogId.showModal();
}

const pageNumberElement = document.body.querySelector('#page_number');

function submitCatcher(change) {
    pageNumberElement.value = String(Number(pageNumberElement.value) + change);
}