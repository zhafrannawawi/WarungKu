function togglePasswordVisibility() {
    const passInput = document.getElementById('user_password');
    const eyeIcon = document.getElementById('eye-icon');

    if (!passInput) return;

    if (passInput.type === 'password') {
        passInput.type = 'text';
        if (eyeIcon) {
            eyeIcon.setAttribute('data-lucide', 'eye-off');
            if (window.lucide) lucide.createIcons();
        }
    } else {
        passInput.type = 'password';
        if (eyeIcon) {
            eyeIcon.setAttribute('data-lucide', 'eye');
            if (window.lucide) lucide.createIcons();
        }
    }
}
