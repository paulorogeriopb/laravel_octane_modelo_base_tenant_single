function languageSelect() {
    return {
        open: false,
        selected: {
            code: "{{ app()->getLocale() }}",
            name: "",
            flag: "",
        },
        languages: [
            {
                code: "pt_BR",
                name: "Português (BR)",
                flag: "/images/flags/br.png",
            },
            {
                code: "en",
                name: "English",
                flag: "/images/flags/us.png",
            },
            {
                code: "es",
                name: "Español",
                flag: "/images/flags/es.png",
            },
        ],
        init() {
            // Inicializa selected com base no código atual
            let current = this.languages.find(
                (l) => l.code === this.selected.code
            );
            if (current) {
                this.selected.name = current.name;
                this.selected.flag = current.flag;
            }
        },
        submitForm() {
            this.open = false;
            this.selected = this.languages.find(
                (l) => l.code === this.selected.code
            );
            // Submete o form com o idioma selecionado
            this.$el.submit();
        },
    };
}
