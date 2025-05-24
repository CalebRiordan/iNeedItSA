document.addEventListener('DOMContentLoaded', () => {
    const userProfile = document.querySelector('.nav-link.user-profile');
    const sidebar = document.querySelector('.sidebar');
    const overlay = document.querySelector('.sidebar-overlay');

    userProfile.addEventListener('click', () => {
        console.log("show");
        
        sidebar.classList.add('show');
        overlay.classList.add('show');
    });
    
    overlay.addEventListener('click', () => {
        console.log("hide");
        sidebar.classList.remove('show');
        overlay.classList.remove('show');
    });
});