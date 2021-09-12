const mix = require('laravel-mix');

mix.js('resources/js/app/main.js', 'public/js/app.js').vue().extract([
    'vue',
    'vue-meta',
    'lodash',
    '@inertiajs/inertia',
    '@inertiajs/progress',
    '@inertiajs/inertia-vue',
    '@jiannius/ui',
]);

mix.postCss('resources/css/app.css', 'public/css', [
    require('postcss-import'),
    require('tailwindcss'),
])

mix.copy('resources/js/web/main.js', 'public/js/web.js')

mix.webpackConfig(require('./webpack.config'));

if (mix.inProduction()) {
    mix.version();
}