import { defineConfig } from "vite";
import laravel, { refreshPaths } from "laravel-vite-plugin";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                "resources/css/app.css",
                "resources/js/app.js",
                "./vendor/filament/**/*.blade.php",
                "./vendor/danharrin/filament-blog/resources/views/**/*.blade.php",
                "./app/Filament/**/*.php",
            ],
            refresh: [...refreshPaths, "app/Livewire/**"],
        }),
    ],
});
