const idInput = document.getElementById("id-copy");
const addressInput = document.getElementById("poa");
const submitBtn = document.querySelector(".seller-reg-form button");

function toggleButton() {
    console.log("toggle");
    
    if (idInput.files.length > 0 && addressInput.files.length > 0) {
        submitBtn.classList.remove("disabled");
    } else {
        submitBtn.classList.add("disabled");
    }
}

idInput.addEventListener("change", toggleButton);
addressInput.addEventListener("change", toggleButton);

// Initial state
toggleButton();
