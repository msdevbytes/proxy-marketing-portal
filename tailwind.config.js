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
        "./vendor/danharrin/filament-blog/resources/views/**/*.blade.php",
    ],
    theme: {
        extend: {},
    },
    plugins: [],
};
