import { anySpecialChars } from "/js/utils/sanitisation.js";

// Form inputs
const phoneNumInput = document.querySelector(".input-group .phone-no");
const checkbox = document.getElementById("same-address");
const addressInput = document.getElementById("address");
const shipAddressInput = document.getElementById("ship-address");

// Image
const preview = document.getElementById("profile-pic-preview");
const profilePicInput = document.getElementById("profile-pic");
const image = document.getElementById("img-container");
const removeImageBtn = document.querySelector(".image-input .remove-btn");
const imageChangedEl = document.getElementById("image-changed");

// Submit button
const register = document.querySelector(".form-container #btn-submit");
let formValid = true;

function previewProfilePic(event) {
  const input = event.target;
  if (input.files && input.files[0]) {
    const reader = new FileReader();
    reader.onload = function (e) {
      selectImage(e.target.result);  
    };
    reader.readAsDataURL(input.files[0]);

    document.querySelector(".image-error").textContent = "";
  }
}
window.previewProfilePic = previewProfilePic;

function selectImage(src) {
  preview.src = src;
  preview.classList.remove("placeholder");
  removeImageBtn.style.display = "block";
  if (imageChangedEl) {
    imageChangedEl.value = true;
  }
}

function removeImage() {
  profilePicInput.value = "";
  preview.src = "";
  removeImageBtn.style.display = "none";
  preview.classList.add("placeholder");
  if (imageChangedEl) {
    imageChangedEl.value = true;
  }
}

function syncAddressInput() {
  shipAddressInput.value = addressInput.value;
  shipAddressInput.dispatchEvent(new Event("input"));
}

function addressInputEventListener(enabled = true) {
  if (enabled) {
    addressInput.addEventListener("input", syncAddressInput);
  } else {
    addressInput.removeEventListener("input", syncAddressInput);
  }
}

function setSameAddressCheckbox() {
  if (checkbox.checked) {
    shipAddressInput.value = address.value;
    shipAddressInput.readOnly = true;
    shipAddressInput.classList.add("disabled");
    addressInputEventListener();
  } else {
    shipAddressInput.readOnly = false;
    shipAddressInput.classList.remove("disabled");
    addressInputEventListener(false);
  }
}

function showError(field, message) {
  formValid = false;
  const error = field.closest(".input-group").querySelector(".error");
  error.textContent = message;

  // Define the handler so it can be removed later
  function clearErrorHandler() {
    error.textContent = "";
    field.removeEventListener("input", clearErrorHandler);
  }

  field.addEventListener("input", clearErrorHandler);
}

// Event Listeners

image.addEventListener("click", () => profilePicInput.click());
profilePicInput.addEventListener("change", previewProfilePic);

phoneNumInput.addEventListener("input", function () {
  document.querySelector(".error-phone-no").textContent = "";

  this.value = this.value.replace(/\D/g, "");

  if (this.value.length > 9) {
    this.value = this.value.slice(0, 9);
  }

  // If the input is empty, set to "0"
  if (this.value === "") {
    this.value = "0";
    this.setSelectionRange(1, 1);
  } else if (this.value.length === 2 && this.value.startsWith("0")) {
    // If the first char is "0", replace the user input
    this.value = this.value[1];
    this.setSelectionRange(1, 1);
  }
});

phoneNumInput.addEventListener("blur", function () {
  if (this.value.length < 9 && this.value.length > 1) {
    document.querySelector(".error-phone-no").textContent =
      "Phone number must be 9 digits";
  }
});

checkbox.addEventListener("change", setSameAddressCheckbox);

register.addEventListener("click", (e) => {
  formValid = true;

  document.querySelectorAll("[required]").forEach((field) => {
    if (!formValid) return;
    
    const val = field.value.trim();
    if (val === "") {
      showError(field, "Please fill out this field.");
    } else if (
      anySpecialChars(val) &&
      field.querySelector("#password") !== null
    )
      showError(field, "Please avoid using special characters.");
  });
});

removeImageBtn.addEventListener("click", removeImage);

setSameAddressCheckbox();
