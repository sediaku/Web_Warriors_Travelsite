document.getElementById('signup-form').addEventListener('submit', function (event) {
    event.preventDefault();
    document.querySelectorAll('.error').forEach(function (error) {
        error.remove();
    });

    var isValid = true;

    let fname = document.querySelector('#signup-fname input').value;
    let lname = document.querySelector('#signup-lname input').value;
    let email = document.querySelector('#signup-email input').value;
    let password = document.querySelector('#signup-pass input').value;
    let confirm = document.querySelector('#signup-confirm input').value;

    if (fname === "") {
        errorMessage('signup-fname', 'Enter your first name');
        isValid = false; // Mark the form as invalid
    }

    if (lname === "") {
        errorMessage('signup-lname', 'Enter your last name');
        isValid = false; // Mark the form as invalid
    }

    if (!validateEmail(email)) {
        errorMessage('signup-email', 'Enter a valid email');
        isValid = false; // Mark the form as invalid
    }

    if (!validatePassword(password)) {
        isValid = false;
    }

    if (password !== confirm) {
        errorMessage('signup-confirm', 'Passwords do not match');
        isValid = false;
    }
    
    if (isValid) {
        this.submit();
    }
});

function validateEmail(email) {
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return regex.test(email);
}

function validatePassword(password) {
    if (password.length < 8) {
        errorMessage('signup-pass', 'Password must be at least 8 characters');
        return false;
    }

    if (!/[A-Z]/.test(password)) {
        errorMessage('signup-pass', 'Password must contain a capital letter');
        return false;
    }

    if ((password.match(/\d/g) || []).length < 3) {
        errorMessage('signup-pass', 'Password must contain at least three digits');
        return false;
    }

    if (!/[!"£$%^&*(){}@<>?+_-]/.test(password)) {
        errorMessage('signup-pass', 'Password must contain at least one the following special characters: !"£$%^&*(){}@<>?+_-');
        return false;
    }
    return true; // Valid password
}

// Function to display an error message for a specific form field
function errorMessage(fieldId, message) {
    const field = document.getElementById(fieldId);
    const error = document.createElement("span");
    error.textContent = message;
    error.className = "error";
    field.insertAdjacentElement("afterend", error);
}
