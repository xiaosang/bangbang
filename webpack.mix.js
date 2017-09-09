const { mix } = require('laravel-mix');

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

mix.js('resources/assets/js/weixin/app.js', 'public/js/weixin.js')
	.js('resources/assets/js/admin/app.js', 'public/js/admin.js')
   .sass('resources/assets/sass/app.scss', 'public/css')
   .extract(['vue', 'element-ui', 'vue-router']);
if (mix.config.inProduction) {
    mix.version();
    mix.disableNotifications();
}