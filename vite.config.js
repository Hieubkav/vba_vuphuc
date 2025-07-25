import { defineConfig } from 'vite';
import laravel, { refreshPaths } from 'laravel-vite-plugin'

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/performance.css',
                'resources/js/app.js'
            ],
            refresh: [
                ...refreshPaths,
                'app/Livewire/**',
                'resources/views/**/*.blade.php',
                'app/Http/Controllers/**',
            ],
        }),
    ],
});
