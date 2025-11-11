const mix = require('laravel-mix');
const path = require('path');

mix.js('resources/js/admin.js', 'assets/js/admin.js')
    .vue({ version: 3 }) // Ensure Vue 3 compatibility
    .sass('resources/scss/admin/admin.scss', 'assets/css/admin.css')
    .sass('resources/scss/frontend/campaign_list.scss', 'assets/css/frontend/campaign_list.css')
    .sass('resources/scss/frontend/campaign_details.scss', 'assets/css/frontend/campaign_details.css')
    //    .copy('resources/images', 'assets/images')  
    .js('resources/js/campaign/campaign_details.js', 'assets/js/campaign/campaign_details.js')
    .sourceMaps(); 


mix.webpackConfig({
    resolve: {
        extensions: ['.js', '.vue', '.json'], 
        alias: {
            '@': path.resolve(__dirname, 'resources/js')
        }
    }
});