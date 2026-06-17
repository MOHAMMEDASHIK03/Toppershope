import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/th-pickers.js',
                'resources/js/th-select.js',
            ],
            refresh: true,
            buildDirectory: 'build',
        }),
        tailwindcss(),
    ],

    build: {
        outDir: '../public_html/build',
        emptyOutDir: true,
    },

    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});