// Mantém o spinner visível inicialmente e esconde ao carregar a página
window.addEventListener("load", function () {
    const spinner = document.getElementById("global-spinner");
    if (spinner) {
        spinner.classList.add("hidden"); // esconde o spinner
    }
});
