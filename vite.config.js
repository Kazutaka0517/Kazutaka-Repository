import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/css/routePlanner.css',
                'resources/js/routePlanner.js',
            ],
            refresh: true,
        }),
    ],
});
