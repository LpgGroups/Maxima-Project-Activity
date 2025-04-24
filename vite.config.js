import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                "resources/src/input.css",
                "resources/js/app.js",
                "resources/js/authentifikasi.js",
                "resources/js/datepicker.js",
                "resources/js/registertraining.js",
            ],
            refresh: true,
        }),
    ],
});
