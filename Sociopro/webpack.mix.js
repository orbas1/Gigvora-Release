const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/js').postCss('resources/css/app.css', 'public/css', [
    require('postcss-import'),
    require('tailwindcss'),
    require('autoprefixer'),
]);

mix.js('Gigvora-Addons-main/Advertisement-Addon/Advertisement_Laravel_package/resources/js/advertisement/dashboard.js', 'public/js/advertisement');
mix.js('Gigvora-Addons-main/Advertisement-Addon/Advertisement_Laravel_package/resources/js/advertisement/campaigns.js', 'public/js/advertisement');
mix.js('Gigvora-Addons-main/Advertisement-Addon/Advertisement_Laravel_package/resources/js/advertisement/wizard.js', 'public/js/advertisement');
mix.js('Gigvora-Addons-main/Advertisement-Addon/Advertisement_Laravel_package/resources/js/advertisement/creatives.js', 'public/js/advertisement');
mix.js('Gigvora-Addons-main/Advertisement-Addon/Advertisement_Laravel_package/resources/js/advertisement/keyword_planner.js', 'public/js/advertisement');
mix.js('Gigvora-Addons-main/Advertisement-Addon/Advertisement_Laravel_package/resources/js/advertisement/forecast.js', 'public/js/advertisement');
mix.js('Gigvora-Addons-main/Advertisement-Addon/Advertisement_Laravel_package/resources/js/advertisement/admin.js', 'public/js/advertisement');

mix.js('Gigvora-Addons-main/Ai-Headhunter-Launchpad-Addon/Ai-Headhunter-E_Launchpad-Laravel-Addon/resources/js/addons/talent_ai/pipeline_board.js', 'public/js/addons/talent_ai');
mix.js('Gigvora-Addons-main/Ai-Headhunter-Launchpad-Addon/Ai-Headhunter-E_Launchpad-Laravel-Addon/resources/js/addons/talent_ai/launchpad_progress.js', 'public/js/addons/talent_ai');
mix.js('Gigvora-Addons-main/Ai-Headhunter-Launchpad-Addon/Ai-Headhunter-E_Launchpad-Laravel-Addon/resources/js/addons/talent_ai/ai_workspace.js', 'public/js/addons/talent_ai');
mix.js('Gigvora-Addons-main/Ai-Headhunter-Launchpad-Addon/Ai-Headhunter-E_Launchpad-Laravel-Addon/resources/js/addons/talent_ai/volunteering_filters.js', 'public/js/addons/talent_ai');
mix.js('Gigvora-Addons-main/Ai-Headhunter-Launchpad-Addon/Ai-Headhunter-E_Launchpad-Laravel-Addon/resources/js/addons/talent_ai/admin_settings.js', 'public/js/addons/talent_ai');
mix.js('Gigvora-Addons-main/Ai-Headhunter-Launchpad-Addon/Ai-Headhunter-E_Launchpad-Laravel-Addon/resources/js/addons/talent_ai/talent_ai.js', 'public/js/addons/talent_ai');
mix.postCss('Gigvora-Addons-main/Ai-Headhunter-Launchpad-Addon/Ai-Headhunter-E_Launchpad-Laravel-Addon/resources/css/addons/talent_ai/talent_ai.css', 'public/css/addons/talent_ai', [
    require('postcss-import'),
    require('autoprefixer'),
]);
