<?php

declare(strict_types=1);

namespace Gigvora\TalentAi\Providers;

use Gigvora\TalentAi\Domain\Headhunters\Models\HeadhunterCandidate;
use Gigvora\TalentAi\Domain\Headhunters\Models\HeadhunterMandate;
use Gigvora\TalentAi\Domain\Headhunters\Models\HeadhunterPipelineItem;
use Gigvora\TalentAi\Domain\Headhunters\Models\HeadhunterProfile;
use Gigvora\TalentAi\Domain\Launchpad\Models\LaunchpadApplication;
use Gigvora\TalentAi\Domain\Launchpad\Models\LaunchpadProgramme;
use Gigvora\TalentAi\Domain\Volunteering\Models\VolunteeringApplication;
use Gigvora\TalentAi\Domain\Volunteering\Models\VolunteeringOpportunity;
use Gigvora\TalentAi\Policies\AiAdminPolicy;
use Gigvora\TalentAi\Policies\HeadhunterCandidatePolicy;
use Gigvora\TalentAi\Policies\HeadhunterMandatePolicy;
use Gigvora\TalentAi\Policies\HeadhunterPipelineItemPolicy;
use Gigvora\TalentAi\Policies\HeadhunterProfilePolicy;
use Gigvora\TalentAi\Policies\LaunchpadApplicationPolicy;
use Gigvora\TalentAi\Policies\LaunchpadProgrammePolicy;
use Gigvora\TalentAi\Policies\VolunteeringApplicationPolicy;
use Gigvora\TalentAi\Policies\VolunteeringOpportunityPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class TalentAiServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/gigvora_talent_ai.php', 'gigvora_talent_ai');
    }

    public function boot(): void
    {
        if (config('gigvora_talent_ai.enabled')) {
            $this->loadRoutesFrom(__DIR__ . '/../../routes/addons_talent_ai.php');
        }

        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'talent_ai');
        $this->loadTranslationsFrom(__DIR__ . '/../../resources/lang', 'talent_ai');

        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

        Gate::policy(HeadhunterProfile::class, HeadhunterProfilePolicy::class);
        Gate::policy(HeadhunterMandate::class, HeadhunterMandatePolicy::class);
        Gate::policy(HeadhunterPipelineItem::class, HeadhunterPipelineItemPolicy::class);
        Gate::policy(HeadhunterCandidate::class, HeadhunterCandidatePolicy::class);
        Gate::policy(LaunchpadProgramme::class, LaunchpadProgrammePolicy::class);
        Gate::policy(LaunchpadApplication::class, LaunchpadApplicationPolicy::class);
        Gate::policy(VolunteeringOpportunity::class, VolunteeringOpportunityPolicy::class);
        Gate::policy(VolunteeringApplication::class, VolunteeringApplicationPolicy::class);

        Gate::define('manage_talent_ai', [AiAdminPolicy::class, 'manage']);

        View::composer(['layouts.admin', 'admin.*'], function ($view): void {
            $view->with('talentAiAdminMenu', view('talent_ai::admin.partials.menu'));
        });
    }
}
