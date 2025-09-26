const iconCheck = `<svg class="w-5 h-5 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none"
     viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
</svg>`;

const iconX = `<svg class="w-5 h-5 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none"
     viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
</svg>`;

function setRule(ruleEl, condition) {
    if (!ruleEl) return;
    ruleEl.classList.toggle("text-green-600", condition);
    ruleEl.classList.toggle("text-red-600", !condition);
    const icon = ruleEl.querySelector(".icon");
    if (icon) {
        icon.innerHTML = condition ? iconCheck : iconX;
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
    document.querySelectorAll("[data-password-rules]").forEach((input) => {
        input.addEventListener("input", function () {
            validatePassword(this.value);
        });
    });
});
