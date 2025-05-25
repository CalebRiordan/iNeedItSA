document.addEventListener("DOMContentLoaded", () => {
  const userProfile = document.querySelector(".nav-link.user-profile");
  const sidebar = document.querySelector(".sidebar");
  const overlay = document.querySelector(".sidebar-overlay");
  const closeButton = document.querySelector(".sidebar .close-button");

  userProfile.addEventListener("click", () => {
    sidebar.classList.add("show");
    overlay.classList.add("show");
  });

  overlay.addEventListener("click", () => {
    sidebar.classList.remove("show");
    overlay.classList.remove("show");
  });

  closeButton.addEventListener("click", () => {
    sidebar.classList.remove("show");
    overlay.classList.remove("show");
  });
});
