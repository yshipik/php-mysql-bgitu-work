const adminUsernameItem = document.body.querySelector("#edit_name");
const adminPasswordItem = document.body.querySelector("#edit_description");
const adminEmailItem = document.body.querySelector("#edit_description");
const adminEditRightItem = document.body.querySelector("");
const adminDeleteRightItem = document.body.querySelector("");
const adminBlockAdminRightItem = document.body.querySelector("");
const adminBlockUsersRightItem = document.body.querySelector("");
const adminIdItem = document.body.querySelector("");
const admin = document.body.querySelector("#edit_id");
const editDialogId = document.body.querySelector("#edit");

function displayUpdateModel(id, username, email, password, edit_right, delete_right, user_right, admin_right ) {
  adminIdItem.value = id;
  adminUsernameItem.value = username;
  adminPasswordItem.value = password;
  adminEmailItem.value = email;

  adminEditRightItem.checked = edit_right == 1 ? "on": "off";
  adminDeleteRightItem.checked = delete_right == 1 ? "on": "off";
  adminBlockUsersRightItem.checked = user_right == 1 ? "on": "off";
  adminBlockAdminRightItem.checked = admin_right == 1 ? "on": "off";
  editDialogId.showModal();
}

const pageNumberElement = document.body.querySelector("#page_number");

function submitCatcher(change) {
  pageNumberElement.value = String(Number(pageNumberElement.value) + change);
}
