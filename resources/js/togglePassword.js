function togglePassword() {
    const input = document.getElementById("password");
    const eyeOpen = document.getElementById("eye-open");
    const eyeSlash = document.getElementById("eye-slash");

    if (!input || !eyeOpen || !eyeSlash) return;

    if (input.type === "password") {
        input.type = "text";
        eyeOpen.classList.add("hidden");
        eyeSlash.classList.remove("hidden");
    } else {
        input.type = "password";
        eyeSlash.classList.add("hidden");
        eyeOpen.classList.remove("hidden");
    }
}

// 👉 Torna disponível no escopo global
window.togglePassword = togglePassword;
