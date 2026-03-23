import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/assets/sass/app.scss',
                'resources/assets/js/ddl.js',
                'resources/assets/js/epub.js',
                'resources/assets/js/resource.js',
                'resources/assets/js/tinymce.js',
            ],
            refresh: true,
        }),
    ],
});
