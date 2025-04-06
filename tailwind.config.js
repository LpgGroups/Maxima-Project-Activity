import defaultTheme from 'tailwindcss/defaultTheme';

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
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
                poppins: ["Poppins", "sans-serif"],
                inter:  ['Inter', 'sans-serif'],
            },
            rotate: {
                '180': '180deg',
              }
        },
    },
    plugins: [],
};
