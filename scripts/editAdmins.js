const adminUsernameItem = document.body.querySelector("#edit_name");
const adminPasswordItem = document.body.querySelector("#edit_password");
const adminEmailItem = document.body.querySelector("#edit_email");
const adminEditRightItem = document.body.querySelector("#edit_downloads");
const adminDeleteRightItem = document.body.querySelector("#delete_downloads");
const adminBlockAdminRightItem = document.body.querySelector("#block_admins");
const adminBlockUsersRightItem = document.body.querySelector("#block_users");
const adminIdItem = document.body.querySelector("#edit_id");
const editDialogId = document.body.querySelector("#edit");

function displayUpdateModel(id, username, email, edit_right, delete_right, user_right, admin_right ) {
  adminIdItem.value = id;
  adminUsernameItem.value = username;
  adminEmailItem.value = email;
  
  editDialogId.showModal();
  adminEditRightItem.checked = edit_right == 1 ? 'checked': false;
  adminDeleteRightItem.checked = delete_right == 1 ? 'checked': false;
  adminBlockUsersRightItem.checked = user_right == 1 ? 'checked': false;
  adminBlockAdminRightItem.checked = admin_right == 1 ? true: false;
}

const pageNumberElement = document.body.querySelector("#page_number");

function submitCatcher(change) {
  pageNumberElement.value = String(Number(pageNumberElement.value) + change);
}
