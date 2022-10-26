/** @type {import('tailwindcss').Config} */
const defaultTheme = require("tailwindcss/defaultTheme");

module.exports = {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
        "./resources/js/**/*.jsx",
    ],

    theme: {
        container: {
            center: true,
        },
        extend: {
            fontFamily: {
                sans: ["Nunito", ...defaultTheme.fontFamily.sans],
            },
            screens: {
                sm: "640px",
                md: "728px",
                lg: "984px",
                xl: "1020px",
                "2xl": "1080px",
            },
        },
    },
    plugins: [require("@tailwindcss/typography")],
};
