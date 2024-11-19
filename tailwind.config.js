/** @type {import('tailwindcss').Config} */
import preset from "./vendor/filament/support/tailwind.config.preset";
export default {
    presets: [preset],
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
        "./app/Filament/**/*.php",
        "./resources/views/filament/**/*.blade.php",
        "./resources/**/*.blade.php",
        "./vendor/filament/**/*.blade.php",
        "./app/Filament/**/*.php",
    ],
    theme: {
        extend: {
            screens: {
                xs: "380px",
                sm: "540px",
                md: "720px",
                lg: "920px",
                xl: "1040px",
            },
        },
    },
    plugins: [],
};
