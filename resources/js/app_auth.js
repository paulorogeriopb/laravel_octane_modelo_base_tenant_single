// Aplica o tema antes de qualquer renderização
(function () {
    try {
        const isDark = localStorage.getItem("theme") === "dark";
        document.documentElement.classList.toggle("dark", isDark);
    } catch (_) {
        // fallback silencioso
    }
})();

document.addEventListener("DOMContentLoaded", () => {
    const htmlElement = document.documentElement;
    const toggleBtn = document.getElementById("toggleThemeBtn");
    const iconMoon = document.getElementById("iconMoon");
    const iconSun = document.getElementById("iconSun");

    // Dropdown elements
    const dropdownButton = document.getElementById("userDropdownButton");
    const dropdownContent = document.getElementById("dropdownContent");

    // Sidebar elements
    const hamburgerButton = document.getElementById("hamburgerButton");
    const sidebar = document.getElementById("sidebar");
    const closeSidebar = document.getElementById("closeSidebar");

    // Atualiza ícones de acordo com o tema atual
    function updateIcons() {
        const darkActive = htmlElement.classList.contains("dark");
        if (iconMoon && iconSun) {
            iconMoon.classList.toggle("hidden", darkActive);
            iconSun.classList.toggle("hidden", !darkActive);
        }
    }
    updateIcons();

    // Toggle do tema (claro/escuro)
    if (toggleBtn) {
        toggleBtn.addEventListener("click", () => {
            htmlElement.classList.toggle("dark");
            const darkNow = htmlElement.classList.contains("dark");
            localStorage.setItem("theme", darkNow ? "dark" : "light");
            updateIcons();
        });
    }

    // Comportamento do menu dropdown
    if (dropdownButton && dropdownContent) {
        let dropdownTimeout;

        dropdownButton.addEventListener("mouseenter", () => {
            clearTimeout(dropdownTimeout);
            dropdownContent.classList.remove("hidden");
        });

        dropdownContent.addEventListener("mouseenter", () => {
            clearTimeout(dropdownTimeout);
            dropdownContent.classList.remove("hidden");
        });

        const scheduleDropdownHide = () => {
            dropdownTimeout = setTimeout(() => {
                if (
                    !dropdownContent.matches(":hover") &&
                    !dropdownButton.matches(":hover")
                ) {
                    dropdownContent.classList.add("hidden");
                }
            }, 150);
        };

        dropdownButton.addEventListener("mouseleave", scheduleDropdownHide);
        dropdownContent.addEventListener("mouseleave", scheduleDropdownHide);

        dropdownButton.addEventListener("click", () => {
            dropdownContent.classList.toggle("hidden");
        });

        dropdownContent.querySelectorAll("a").forEach((link) => {
            link.addEventListener("click", () => {
                dropdownContent.classList.add("hidden");
            });
        });
    }

    // Comportamento do sidebar em mobile
    if (hamburgerButton && sidebar && closeSidebar) {
        hamburgerButton.addEventListener("click", () => {
            sidebar.classList.toggle("sidebar-open");
        });

        closeSidebar.addEventListener("click", () => {
            sidebar.classList.remove("sidebar-open");
        });

        document.addEventListener("click", (event) => {
            if (
                !sidebar.contains(event.target) &&
                !hamburgerButton.contains(event.target) &&
                sidebar.classList.contains("sidebar-open")
            ) {
                sidebar.classList.remove("sidebar-open");
            }
        });
    }
});
