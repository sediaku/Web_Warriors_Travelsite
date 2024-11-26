document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.querySelector('.form');
    
    loginForm.addEventListener('submit', function(event) {
        
        clearErrors();
        
       
        const username = document.getElementById('username');
        if (!validateUsername(username.value)) {
            showError(username, 'Username must be 3-20 characters long and contain only letters, numbers, and underscores');
            event.preventDefault();
            return;
        }
        
       
        const password = document.getElementById('password');
        if (!validatePassword(password.value)) {
            showError(password, 'Password must be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, and one number');
            event.preventDefault();
            return;
        }
    });

    function validateUsername(username) {
        
        const usernameRegex = /^[a-zA-Z0-9_]{3,20}$/;
        return usernameRegex.test(username);
    }

    function validatePassword(password) {
        
        const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/;
        return passwordRegex.test(password);
    }

    function showError(inputElement, message) {
        
        let errorElement = inputElement.nextElementSibling;
        if (!errorElement || !errorElement.classList.contains('validation-error')) {
            errorElement = document.createElement('span');
            errorElement.classList.add('validation-error');
            inputElement.parentNode.insertBefore(errorElement, inputElement.nextSibling);
        }
        
        
        errorElement.textContent = message;
        errorElement.style.color = 'red';
        inputElement.style.borderColor = 'red';
    }

    function clearErrors() {
        
        const errorElements = document.querySelectorAll('.validation-error');
        errorElements.forEach(el => el.remove());
        
        
        const inputs = document.querySelectorAll('input');
        inputs.forEach(input => {
            input.style.borderColor = '';
        });
    }
});