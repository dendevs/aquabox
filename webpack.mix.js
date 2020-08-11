const mix = require('laravel-mix');

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
mix.browserSync('aquabox.local');

mix.js('resources/js/app.js', 'public/js')
    .js('resources/js/admin/actions_device.js', 'public/js/admin')
    .sass('resources/sass/app.scss', 'public/css');
