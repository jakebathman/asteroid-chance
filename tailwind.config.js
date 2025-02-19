import defaultTheme from "tailwindcss/defaultTheme";

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ["Figtree", ...defaultTheme.fontFamily.sans],
                doto: ["Doto", ...defaultTheme.fontFamily.sans],
            },
            animation: {
                "meteor-effect": "meteor 15s linear infinite",
            },
            keyframes: {
                meteor: {
                    "0%": {
                        transform: "rotate(0deg) translateX(0)",
                        opacity: 1,
                    },
                    "70%": { opacity: 1 },
                    "100%": {
                        transform: "rotate(0deg) translateX(-2000px)",
                        opacity: 0,
                    },
                },
            },
        },
    },
    plugins: [],
};
