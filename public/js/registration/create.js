const phoneNumInput = document.querySelector(".input-group .phone-no");
const checkbox = document.getElementById("same-address");
const addressInput = document.getElementById("address");
const shipAddressInput = document.getElementById("ship-address");

function previewProfilePic(event) {
  const input = event.target;
  const preview = document.getElementById("profile-pic-preview");
  const placeholder = document.getElementById("profile-pic-placeholder");
  if (input.files && input.files[0]) {
    const reader = new FileReader();
    reader.onload = function (e) {
      preview.src = e.target.result;
      preview.style.display = "block";
      // Optionally remove background image
      placeholder.style.backgroundImage = "none";
    };
    reader.readAsDataURL(input.files[0]);
  }
}

function syncAddressInput() {
  shipAddressInput.value = addressInput.value;
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
    console.log("disabled");
    shipAddressInput.disabled = true;
    addressInputEventListener();
  } else {
    console.log("enabled");
    shipAddressInput.disabled = false;
    addressInputEventListener(false);
  }
}

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

setSameAddressCheckbox();
