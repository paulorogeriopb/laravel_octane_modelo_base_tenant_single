export function languageSelect(locale) {
    return {
        open: false,
        selected: { code: locale, name: "", flag: "" },
        languages: [
            {
                code: "pt_BR",
                name: "Português (BR)",
                flag: "/images/flags/br.png",
            },
            { code: "en", name: "English", flag: "/images/flags/us.png" },
            { code: "es", name: "Español", flag: "/images/flags/es.png" },
        ],
        init() {
            const current = this.languages.find(
                (l) => l.code === this.selected.code
            );
            if (current) {
                this.selected.name = current.name;
                this.selected.flag = current.flag;
            }
        },
        submitForm() {
            const current = this.languages.find(
                (l) => l.code === this.selected.code
            );
            if (current) this.selected = current;
            this.$el.submit();
        },
    };
}
