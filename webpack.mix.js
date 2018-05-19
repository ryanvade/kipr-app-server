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
mix.copy('node_modules/pdfjs-dist/build/pdf.min.js', 'public/js');
mix.copy('node_modules/pdfjs-dist/build/pdf.worker.min.js', 'public/js');
mix.copy('node_modules/font-awesome/css/font-awesome.min.css', 'public/css');

mix.js('resources/assets/js/app.js', 'public/js')
  .js('resources/assets/js/notification.js', 'public/js')
  .sass('resources/assets/sass/app.scss', 'public/css');

mix.copyDirectory('resources/assets/images', 'public/images');
// mix.browserSync('kipr-app.dev');
