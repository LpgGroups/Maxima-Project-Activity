import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                "resources/css/app.css",
                "resources/js/app.js",
                "resources/js/authentifikasi.js",
                "resources/js/datepicker.js",
                "resources/js/registertraining.js",
            ],
            refresh: true,
        }),
    ],

    // server: {
    //     cors: {
    //         origin: "https://test.maximagroup.co.id",  // Ganti dengan domain asal yang tepat
    //     },
    // },

    build: {
        outDir: "public/build",
        manifest: true,
        rollupOptions: {
            input: {
                app: "resources/js/app.js",
                authentifikasi: "resources/js/authentifikasi.js",
                datepicker: "resources/js/datepicker.js",
                registertraining: "resources/js/registertraining.js",
                css: "resources/css/app.css",
            },
        },
    },
});
