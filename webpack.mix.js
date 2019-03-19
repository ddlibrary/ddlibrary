let mix = require('laravel-mix');

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

mix.styles([
   'resources/assets/css/reset.css',
   'resources/assets/css/common.css',
   'resources/assets/css/ddl.css',
   'resources/assets/css/survey.css',
   'resources/assets/css/fontawesome-all.min.css',
], 'public/css/all.css');

mix.babel([
   'public/js/ddl.js',
   'resources/assets/js/lazysizes.min.js',
   'public/js/bootstrap.min.js',
   ], 'public/js/all.js');
