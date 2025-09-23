import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                "resources/css/app.css",
                "resources/js/app.js",
                "resources/js/no-alert.js",
                "resources/js/bootstrap.js",
                "resources/js/app_auth.js",
                "resources/js/alert-delete.js",
                "resources/js/password-rules.js",
            ],
            refresh: true,
        }),
    ],
    build: {
        outDir: "public/build",
        emptyOutDir: true,
    },
});
