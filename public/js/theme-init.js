(function () {
    try {
        const theme = localStorage.getItem("theme");
        const prefersDark = window.matchMedia(
            "(prefers-color-scheme: dark)"
        ).matches;
        const shouldUseDark =
            theme === "dark" || (theme === null && prefersDark);

        if (shouldUseDark) {
            document.documentElement.classList.add("dark");
        } else {
            document.documentElement.classList.remove("dark");
        }
    } catch (_) {
        // fallback silencioso
    }
})();
