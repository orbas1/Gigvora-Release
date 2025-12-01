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

mix.js('resources/js/freelance/app.js', 'public/js/freelance')
    .postCss('resources/css/freelance/app.css', 'public/css/freelance', [
        require('postcss-import'),
        require('autoprefixer'),
    ]);

mix.js('resources/js/live/app.js', 'public/js/live')
    .postCss('resources/css/live/app.css', 'public/css/live', [
        require('postcss-import'),
        require('autoprefixer'),
    ]);

mix.js('Gigvora-Addons/Interactive-Addon/Webinar_networking_interview_and_Podcast_Laravel_package/resources/js/live/interviewDashboard.js', 'public/js/live');
mix.js('Gigvora-Addons/Interactive-Addon/Webinar_networking_interview_and_Podcast_Laravel_package/resources/js/live/interviewerScoring.js', 'public/js/live');
mix.js('Gigvora-Addons/Interactive-Addon/Webinar_networking_interview_and_Podcast_Laravel_package/resources/js/live/podcastPlayer.js', 'public/js/live');

mix.js('Gigvora-Addons/Advertisement-Addon/Advertisement_Laravel_package/resources/js/advertisement/dashboard.js', 'public/js/advertisement');
mix.js('Gigvora-Addons/Advertisement-Addon/Advertisement_Laravel_package/resources/js/advertisement/campaigns.js', 'public/js/advertisement');
mix.js('Gigvora-Addons/Advertisement-Addon/Advertisement_Laravel_package/resources/js/advertisement/wizard.js', 'public/js/advertisement');
mix.js('Gigvora-Addons/Advertisement-Addon/Advertisement_Laravel_package/resources/js/advertisement/creatives.js', 'public/js/advertisement');
mix.js('Gigvora-Addons/Advertisement-Addon/Advertisement_Laravel_package/resources/js/advertisement/keyword_planner.js', 'public/js/advertisement');
mix.js('Gigvora-Addons/Advertisement-Addon/Advertisement_Laravel_package/resources/js/advertisement/forecast.js', 'public/js/advertisement');
mix.js('Gigvora-Addons/Advertisement-Addon/Advertisement_Laravel_package/resources/js/advertisement/admin.js', 'public/js/advertisement');
mix.postCss('Gigvora-Addons/Advertisement-Addon/Advertisement_Laravel_package/resources/css/advertisement/addon.css', 'public/css/advertisement', [
    require('postcss-import'),
    require('autoprefixer'),
]);

mix.js('resources/js/utilities/bubble.js', 'public/js/utilities');
mix.js('resources/js/utilities/notifications.js', 'public/js/utilities');
mix.js('resources/js/utilities/composer.js', 'public/js/utilities');

mix.js('Gigvora-Addons/Ai-Headhunter-Launchpad-Addon/Ai-Headhunter-E_Launchpad-Laravel-Addon/resources/js/addons/talent_ai/pipeline_board.js', 'public/js/addons/talent_ai');
mix.js('Gigvora-Addons/Ai-Headhunter-Launchpad-Addon/Ai-Headhunter-E_Launchpad-Laravel-Addon/resources/js/addons/talent_ai/launchpad_progress.js', 'public/js/addons/talent_ai');
mix.js('Gigvora-Addons/Ai-Headhunter-Launchpad-Addon/Ai-Headhunter-E_Launchpad-Laravel-Addon/resources/js/addons/talent_ai/ai_workspace.js', 'public/js/addons/talent_ai');
mix.js('Gigvora-Addons/Ai-Headhunter-Launchpad-Addon/Ai-Headhunter-E_Launchpad-Laravel-Addon/resources/js/addons/talent_ai/volunteering_filters.js', 'public/js/addons/talent_ai');
mix.js('Gigvora-Addons/Ai-Headhunter-Launchpad-Addon/Ai-Headhunter-E_Launchpad-Laravel-Addon/resources/js/addons/talent_ai/admin_settings.js', 'public/js/addons/talent_ai');
mix.js('Gigvora-Addons/Ai-Headhunter-Launchpad-Addon/Ai-Headhunter-E_Launchpad-Laravel-Addon/resources/js/addons/talent_ai/talent_ai.js', 'public/js/addons/talent_ai');
mix.postCss('Gigvora-Addons/Ai-Headhunter-Launchpad-Addon/Ai-Headhunter-E_Launchpad-Laravel-Addon/resources/css/addons/talent_ai/talent_ai.css', 'public/css/addons/talent_ai', [
    require('postcss-import'),
    require('autoprefixer'),
]);

mix.js('Gigvora-Addons/Jobs-Addon/Jobs_Laravel_package/resources/js/jobs/jobsSearch.js', 'public/js/jobs');
mix.js('Gigvora-Addons/Jobs-Addon/Jobs_Laravel_package/resources/js/jobs/jobDetail.js', 'public/js/jobs');
mix.js('Gigvora-Addons/Jobs-Addon/Jobs_Laravel_package/resources/js/jobs/jobApplyWizard.js', 'public/js/jobs');
mix.js('Gigvora-Addons/Jobs-Addon/Jobs_Laravel_package/resources/js/jobs/jobPostWizard.js', 'public/js/jobs');
mix.js('Gigvora-Addons/Jobs-Addon/Jobs_Laravel_package/resources/js/jobs/employerDashboard.js', 'public/js/jobs');
mix.js('Gigvora-Addons/Jobs-Addon/Jobs_Laravel_package/resources/js/jobs/atsBoard.js', 'public/js/jobs');
mix.js('Gigvora-Addons/Jobs-Addon/Jobs_Laravel_package/resources/js/jobs/interviewCalendar.js', 'public/js/jobs');
mix.js('Gigvora-Addons/Jobs-Addon/Jobs_Laravel_package/resources/js/jobs/cvBuilder.js', 'public/js/jobs');
mix.js('Gigvora-Addons/Jobs-Addon/Jobs_Laravel_package/resources/js/jobs/coverLetterEditor.js', 'public/js/jobs');
mix.js('Gigvora-Addons/Jobs-Addon/Jobs_Laravel_package/resources/js/jobs/screeningBuilder.js', 'public/js/jobs');
mix.js('Gigvora-Addons/Jobs-Addon/Jobs_Laravel_package/resources/js/jobs/adminJobsDashboard.js', 'public/js/jobs');
