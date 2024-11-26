document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.querySelector('.form');
    
    loginForm.addEventListener('submit', function(event) {
        // Reset previous error messages
        clearErrors();
        
        // Validate username
        const username = document.getElementById('username');
        if (!validateUsername(username.value)) {
            showError(username, 'Username must be 3-20 characters long and contain only letters, numbers, and underscores');
            event.preventDefault();
            return;
        }
        
        // Validate password
        const password = document.getElementById('password');
        if (!validatePassword(password.value)) {
            showError(password, 'Password must be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, and one number');
            event.preventDefault();
            return;
        }
    });

    function validateUsername(username) {
        // Username must be 3-20 characters long, contain only letters, numbers, and underscores
        const usernameRegex = /^[a-zA-Z0-9_]{3,20}$/;
        return usernameRegex.test(username);
    }

    function validatePassword(password) {
        // Password must be at least 8 characters long and contain at least one uppercase, 
        // one lowercase letter, and one number
        const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/;
        return passwordRegex.test(password);
    }

    function showError(inputElement, message) {
        // Create error message element if it doesn't exist
        let errorElement = inputElement.nextElementSibling;
        if (!errorElement || !errorElement.classList.contains('validation-error')) {
            errorElement = document.createElement('span');
            errorElement.classList.add('validation-error');
            inputElement.parentNode.insertBefore(errorElement, inputElement.nextSibling);
        }
        
        // Set error message
        errorElement.textContent = message;
        errorElement.style.color = 'red';
        inputElement.style.borderColor = 'red';
    }

    function clearErrors() {
        // Remove all existing error messages
        const errorElements = document.querySelectorAll('.validation-error');
        errorElements.forEach(el => el.remove());
        
        // Reset input border colors
        const inputs = document.querySelectorAll('input');
        inputs.forEach(input => {
            input.style.borderColor = '';
        });
    }
});