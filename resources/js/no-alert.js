document.addEventListener("click", function (e) {
    const el = e.target.closest("button, a, input[type='submit']");

    if (el && el.classList.contains("no-confirm")) {
        sessionStorage.setItem("skipSwalSuccess", "1");
    }
});
