const iconCheck = `
    <svg class="w-5 h-5 text-green-600 icon" xmlns="http://www.w3.org/2000/svg" fill="none"
         viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
    </svg>`;

const iconX = `
    <svg class="w-5 h-5 text-red-600 icon" xmlns="http://www.w3.org/2000/svg" fill="none"
         viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
    </svg>`;

function setRule(ruleEl, condition) {
    if (!ruleEl) return;

    if (condition) {
        ruleEl.classList.remove("text-red-600");
        ruleEl.classList.add("text-green-600");
        ruleEl.querySelector(".icon").outerHTML = iconCheck;
    } else {
        ruleEl.classList.remove("text-green-600");
        ruleEl.classList.add("text-red-600");
        ruleEl.querySelector(".icon").outerHTML = iconX;
    }
}

function validatePassword(password) {
    setRule(document.getElementById("rule-uppercase"), /[A-Z]/.test(password));
    setRule(document.getElementById("rule-lowercase"), /[a-z]/.test(password));
    setRule(document.getElementById("rule-number"), /[0-9]/.test(password));
    setRule(
        document.getElementById("rule-symbol"),
        /[^A-Za-z0-9]/.test(password)
    );
    setRule(document.getElementById("rule-length"), password.length >= 8);
}

document.addEventListener("DOMContentLoaded", () => {
    const passwordInput = document.getElementById("password");
    if (passwordInput) {
        passwordInput.addEventListener("input", function () {
            validatePassword(this.value);
        });
    }
});
