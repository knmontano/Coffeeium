document.addEventListener('DOMContentLoaded', () => {
    let isLoggedIn = false; 

    const alertBox = document.getElementById('alert');
    const itemLinks = document.querySelectorAll('a[data-item-id]');
    const loginModal = document.getElementById('login-modal');
    const userBtnLink = document.getElementById('user-btn'); 
    
    if (!isLoggedIn) {
        loginModal.classList.remove('hidden');
    }
    
    itemLinks.forEach(link => {
        link.addEventListener('click', function(event) {
            event.preventDefault(); 
            
            if (!isLoggedIn) {
                loginModal.classList.remove('hidden');
            } else {
                alertBox.classList.add('show-alert');
                setTimeout(() => {
                    alertBox.classList.remove('show-alert');
                }, 3000); 
            }
        });
    });

    userBtnLink.addEventListener('click', function(event) {
        if (!isLoggedIn) {
             event.preventDefault(); 
             loginModal.classList.remove('hidden');
        }
    });
});