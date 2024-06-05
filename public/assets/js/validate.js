function validateForm() {
    // Reset previous error messages
    document.querySelectorAll('.error').forEach(function (element) {
        element.textContent = '';
    });

    var name = document.getElementById('name').value;
    var last_name = document.getElementById('last_name').value;
    var email = document.getElementById('email').value;
    var phone_number = document.getElementById('phone_number').value;
    var requested = document.getElementById('requested').value;
    var message = document.getElementById('message').value;
    var valid = true;

    // Email validation
    var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!email || !emailPattern.test(email)) {
        document.getElementById('emailError').textContent = 'Please enter a valid email address.';
        valid = false;
    }

    // Phone number validation
    var phoneNumberPattern = /^\d{10}$/; // Change this pattern according to your phone number format
    if (!phone_number || !phoneNumberPattern.test(phone_number)) {
        document.getElementById('numrError').textContent = 'Please enter a valid 10-digit phone number.';
        valid = false;
    }

    // Validation logic for other fields
    if (!name) {
        document.getElementById('nameError').textContent = 'The field is required.';
        valid = false;
    }

    if (!last_name) {
        document.getElementById('last_nameError').textContent = 'The field is required.';
        valid = false;
    }

    if (!requested) {
        document.getElementById('requestedError').textContent = 'The field is required.';
        valid = false;
    }

    if (!message) {
        document.getElementById('messageError').textContent = 'The field is required.';
        valid = false;
    }

    // If form is valid, proceed with submission
    if (valid) {
        document.getElementById('myForm').submit();
    }
}
