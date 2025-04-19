import defaultTheme from "tailwindcss/defaultTheme";

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./public/**/*.js",
        "./resources/**/*.vue",
        "./public/**/*.css",
    ],
    theme: {
        extend: {
            keyframes: {
                "flip-horizontal": {
                    "0%": { transform: "rotateY(0deg)" },
                    "50%": { transform: "rotateY(180deg)" },
                    "100%": { transform: "rotateY(360deg)" },
                },
            },
            animation: {
                "flip-horizontal": "flip-horizontal 3s linear infinite",
            },
            fontFamily: {
                sans: ["Figtree", ...defaultTheme.fontFamily.sans],
                poppins: ["Poppins", "sans-serif"],
                inter: ["Inter", "sans-serif"],
            },
            rotate: {
                180: "180deg",
            },
        },
    },
    plugins: [],
};
