// alert-delete.js
document.addEventListener("submit", function (e) {
    const form = e.target.closest(".form-delete");
    if (!form || form.classList.contains("no-confirm")) return;

    e.preventDefault();

    const isDark = document.documentElement.classList.contains("dark");

    Swal.fire({
        title: "Tem certeza?",
        text: "Essa ação não poderá ser desfeita!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#dd4c6a",
        cancelButtonColor: "#6c757d",
        confirmButtonText: "Sim, apagar!",
        cancelButtonText: "Cancelar",
        background: isDark ? "#1f2937" : "#fff",
        color: isDark ? "#f9fafb" : "#111827",
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });
});
