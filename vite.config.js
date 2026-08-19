import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/aethryna.css',
                'resources/js/app.js',
                // The public site's only bundle. See resources/js/alpine.js.
                'resources/js/alpine.js',
            ],
            refresh: true,
        }),
    ],
});
