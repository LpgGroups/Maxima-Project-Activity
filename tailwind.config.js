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
    safelist: ["cursor-not-allowed"],
    theme: {
        extend: {
            keyframes: {
                "flip-horizontal": {
                    "0%": { transform: "rotateY(0deg)" },
                    "50%": { transform: "rotateY(180deg)" },
                    "100%": { transform: "rotateY(360deg)" },
                },
                "zoom-out": {
                    "0%": { transform: "scale(1.2)" },
                    "100%": { transform: "scale(1)" },
                },
                "zoom-loop": {
                    "0%, 100%": { transform: "scale(1)" },
                    "50%": { transform: "scale(1.05)" },
                },
                fadeInUp: {
                    "0%": { opacity: "0", transform: "translateY(20px)" },
                    "100%": { opacity: "1", transform: "translateY(0)" },
                },
                "slide-left-to-right": {
                    "0%": { transform: "translateX(-100%)", opacity: "0" },
                    "100%": { transform: "translateX(0)", opacity: "1" },
                },
                // Kanan ke Kiri
                "slide-right-to-left": {
                    "0%": { transform: "translateX(100%)", opacity: "0" },
                    "100%": { transform: "translateX(0)", opacity: "1" },
                },
            },
            animation: {
                "flip-horizontal": "flip-horizontal 3s linear infinite",
                "zoom-out": "zoom-out 2s ease-out forwards",
                "zoom-loop": "zoom-loop 10s ease-in-out infinite",
                "slide-left-to-right":
                    "slide-left-to-right 1s ease-out forwards",
                "slide-right-to-left":
                    "slide-right-to-left 1s ease-out forwards",
                fadeInUp: "fadeInUp 1s ease forwards",
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
