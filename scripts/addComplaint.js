const complaintFileIdItem = document.body.querySelector("#edit_file_id");

const createDialogElem = document.body.querySelector("#add_complaint");

function displayComplaintModal( id) {
    complaintFileIdItem.value = id;
    createDialogElem.showModal();
}
