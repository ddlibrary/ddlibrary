let mix = require('laravel-mix');
const fs = require('fs');
const path = require('path');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */
mix.sass('resources/assets/sass/app.scss', 'public/css/all.css')
    .js('resources/assets/js/ddl.js', 'public/js/all.js')
    .js('resources/assets/js/epub.js', 'public/js/epub.js')
    .js('resources/assets/js/resource.js', 'public/js/resource.js')
    .js('resources/assets/js/tinymce.js', 'public/js/tinymce.js')
    .copyDirectory('vendor/tinymce/tinymce', 'public/js/tinymce')
    .then(() => {
        // Clean up manifest: remove TinyMCE directory entries
        const manifestPath = path.resolve(__dirname, 'public/mix-manifest.json');
        if (fs.existsSync(manifestPath)) {
            const manifest = JSON.parse(fs.readFileSync(manifestPath, 'utf8'));
            const filteredManifest = {};

            for (const [key, value] of Object.entries(manifest)) {
                if (!key.startsWith('/js/tinymce/')) {
                    filteredManifest[key] = value;
                }
            }

            fs.writeFileSync(manifestPath, JSON.stringify(filteredManifest, null, 4));
        }
    });
