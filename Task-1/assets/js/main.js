function validateForm() {
    const name = document.getElementById('name').value.trim();
    const email = document.getElementById('email').value.trim();
    const messageDiv = document.getElementById('message');

    if (!name || !email) {
        messageDiv.innerHTML = '<div class="alert alert-danger">All fields are required.</div>';
        return false;
    }

    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailPattern.test(email)) {
        messageDiv.innerHTML = '<div class="alert alert-danger">Invalid email format.</div>';
        return false;
    }

    return true;
}