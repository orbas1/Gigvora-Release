<?php

declare(strict_types=1);

use Gigvora\TalentAi\Http\Controllers\Admin\AdminController;
use Gigvora\TalentAi\Http\Controllers\AiWorkspace\ByokController;
use Gigvora\TalentAi\Http\Controllers\AiWorkspace\StatusController;
use Gigvora\TalentAi\Http\Controllers\AiWorkspace\ToolController;
use Gigvora\TalentAi\Http\Controllers\Headhunters\CandidateController;
use Gigvora\TalentAi\Http\Controllers\Headhunters\HeadhunterProfileController;
use Gigvora\TalentAi\Http\Controllers\Headhunters\InterviewController as HeadhunterInterviewController;
use Gigvora\TalentAi\Http\Controllers\Headhunters\MandateController;
use Gigvora\TalentAi\Http\Controllers\Headhunters\PipelineController;
use Gigvora\TalentAi\Http\Controllers\Launchpad\ApplicationController as LaunchpadApplicationController;
use Gigvora\TalentAi\Http\Controllers\Launchpad\InterviewController as LaunchpadInterviewController;
use Gigvora\TalentAi\Http\Controllers\Launchpad\ProgrammeController;
use Gigvora\TalentAi\Http\Controllers\Volunteering\ApplicationController as VolunteeringApplicationController;
use Gigvora\TalentAi\Http\Controllers\Volunteering\OpportunityController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'addons/talent-ai',
    'middleware' => ['web', 'auth'],
    'as' => 'addons.talent_ai.',
], function () {
    if (!config('gigvora_talent_ai.enabled')) {
        return;
    }

    if (config('gigvora_talent_ai.modules.headhunters.enabled')) {
        Route::post('headhunter/profile', [HeadhunterProfileController::class, 'store'])->name('headhunter.profile.store');
        Route::put('headhunter/profile/{profile}', [HeadhunterProfileController::class, 'update'])->name('headhunter.profile.update');
        Route::post('headhunter/{profile}/mandates', [MandateController::class, 'store'])->name('headhunter.mandate.store');
        Route::put('headhunter/mandates/{mandate}', [MandateController::class, 'update'])->name('headhunter.mandate.update');
        Route::post('headhunter/{profile}/candidates', [CandidateController::class, 'store'])->name('headhunter.candidate.store');
        Route::put('headhunter/candidates/{candidate}', [CandidateController::class, 'update'])->name('headhunter.candidate.update');
        Route::post('headhunter/mandates/{mandate}/pipeline', [PipelineController::class, 'store'])->name('headhunter.pipeline.store');
        Route::post('headhunter/pipeline/{pipelineItem}/move', [PipelineController::class, 'move'])->name('headhunter.pipeline.move');
        Route::post('headhunter/pipeline/{pipelineItem}/interviews', [HeadhunterInterviewController::class, 'store'])->name('headhunter.interview.store');
        Route::put('headhunter/interviews/{interview}', [HeadhunterInterviewController::class, 'update'])->name('headhunter.interview.update');
    }

    if (config('gigvora_talent_ai.modules.launchpad.enabled')) {
        Route::post('launchpad/programmes', [ProgrammeController::class, 'store'])->name('launchpad.programme.store');
        Route::put('launchpad/programmes/{programme}', [ProgrammeController::class, 'update'])->name('launchpad.programme.update');
        Route::post('launchpad/programmes/{programme}/publish', [ProgrammeController::class, 'publish'])->name('launchpad.programme.publish');
        Route::post('launchpad/programmes/{programme}/close', [ProgrammeController::class, 'close'])->name('launchpad.programme.close');
        Route::post('launchpad/programmes/{programme}/applications', [LaunchpadApplicationController::class, 'store'])->name('launchpad.application.store');
        Route::post('launchpad/applications/{application}/status', [LaunchpadApplicationController::class, 'updateStatus'])->name('launchpad.application.status');
        Route::post('launchpad/applications/{application}/interviews', [LaunchpadInterviewController::class, 'store'])->name('launchpad.interview.store');
        Route::put('launchpad/interviews/{interview}', [LaunchpadInterviewController::class, 'update'])->name('launchpad.interview.update');
    }

    if (config('gigvora_talent_ai.modules.volunteering.enabled')) {
        Route::post('volunteering/opportunities', [OpportunityController::class, 'store'])->name('volunteering.opportunity.store');
        Route::put('volunteering/opportunities/{opportunity}', [OpportunityController::class, 'update'])->name('volunteering.opportunity.update');
        Route::post('volunteering/opportunities/{opportunity}/publish', [OpportunityController::class, 'publish'])->name('volunteering.opportunity.publish');
        Route::post('volunteering/opportunities/{opportunity}/close', [OpportunityController::class, 'close'])->name('volunteering.opportunity.close');
        Route::post('volunteering/opportunities/{opportunity}/applications', [VolunteeringApplicationController::class, 'store'])->name('volunteering.application.store');
        Route::post('volunteering/applications/{application}/status', [VolunteeringApplicationController::class, 'updateStatus'])->name('volunteering.application.status');
    }

    Route::middleware('can:manage_talent_ai')->group(function (): void {
        Route::get('admin/config', [AdminController::class, 'index'])->name('admin.config');
        Route::post('admin/plans', [AdminController::class, 'storePlan'])->name('admin.plans.store');
    });
});

Route::group([
    'prefix' => 'api/addons/talent-ai',
    'middleware' => ['api', 'auth:sanctum'],
    'as' => 'api.addons.talent_ai.',
], function () {
    if (!config('gigvora_talent_ai.enabled')) {
        return;
    }

    if (config('gigvora_talent_ai.modules.headhunters.enabled')) {
        Route::get('headhunters/profile', [HeadhunterProfileController::class, 'show'])->name('headhunters.profile.show');
        Route::get('headhunters/mandates', [MandateController::class, 'index'])->name('headhunters.mandates.index');
        Route::get('headhunters/mandates/{mandate}', [MandateController::class, 'show'])->name('headhunters.mandates.show');
        Route::get('headhunters/mandates/{mandate}/pipeline', [PipelineController::class, 'index'])->name('headhunters.pipeline.index');
        Route::post('headhunters/mandates/{mandate}/pipeline/{pipelineItem}', [PipelineController::class, 'move'])->name('headhunters.pipeline.move');
        Route::get('headhunters/candidates/{candidate}', [CandidateController::class, 'show'])->name('headhunters.candidates.show');
        Route::post('headhunters/candidates/{candidate}/notes', [CandidateController::class, 'notes'])->name('headhunters.candidates.notes');
        Route::get('headhunters/candidates/{candidate}/interviews', [HeadhunterInterviewController::class, 'index'])->name('headhunters.candidates.interviews.index');
    }

    if (config('gigvora_talent_ai.modules.launchpad.enabled')) {
        Route::get('launchpad/programmes', [ProgrammeController::class, 'index'])->name('launchpad.programmes.index');
        Route::get('launchpad/programmes/{programme}', [ProgrammeController::class, 'show'])->name('launchpad.programmes.show');
        Route::get('launchpad/programmes/{programme}/tasks', [ProgrammeController::class, 'tasks'])->name('launchpad.programmes.tasks');
        Route::post('launchpad/programmes/{programme}/applications', [LaunchpadApplicationController::class, 'store'])->name('launchpad.applications.store');
        Route::get('launchpad/applications/{application}', [LaunchpadApplicationController::class, 'show'])->name('launchpad.applications.show');
        Route::post('launchpad/applications/{application}/tasks/{task}', [LaunchpadApplicationController::class, 'updateTask'])->name('launchpad.applications.tasks.update');
    }

    if (config('gigvora_talent_ai.modules.volunteering.enabled')) {
        Route::get('volunteering/opportunities', [OpportunityController::class, 'index'])->name('volunteering.opportunities.index');
        Route::get('volunteering/opportunities/{opportunity}', [OpportunityController::class, 'show'])->name('volunteering.opportunities.show');
        Route::post('volunteering/opportunities/{opportunity}/applications', [VolunteeringApplicationController::class, 'store'])->name('volunteering.applications.store');
        Route::get('volunteering/applications', [VolunteeringApplicationController::class, 'index'])->name('volunteering.applications.index');
        Route::get('volunteering/applications/{application}', [VolunteeringApplicationController::class, 'show'])->name('volunteering.applications.show');
    }

    if (config('gigvora_talent_ai.modules.ai_workspace.enabled')) {
        Route::post('ai/cv-writer', [ToolController::class, 'cvWriter'])->name('ai.cv_writer');
        Route::post('ai/outreach', [ToolController::class, 'outreach'])->name('ai.outreach');
        Route::post('ai/social-calendar', [ToolController::class, 'socialCalendar'])->name('ai.social_calendar');
        Route::post('ai/coach', [ToolController::class, 'coach'])->name('ai.coach');
        Route::post('ai/repurpose', [ToolController::class, 'repurpose'])->name('ai.repurpose');
        Route::post('ai/interview-prep', [ToolController::class, 'interviewPrep'])->name('ai.interview_prep');
        Route::post('ai/image-canvas', [ToolController::class, 'imageCanvas'])->name('ai.image_canvas');
        Route::post('ai/writer', [ToolController::class, 'writer'])->name('ai.writer');
        Route::post('ai/marketing-bot', [ToolController::class, 'marketingBot'])->name('ai.marketing_bot');

        Route::post('ai/byok', [ByokController::class, 'store'])->name('ai.byok.store');
        Route::delete('ai/byok/{credential}', [ByokController::class, 'destroy'])->name('ai.byok.destroy');

        Route::get('ai-workspace/sessions', [StatusController::class, 'sessions'])->name('ai.sessions');
        Route::get('ai-workspace/usage', [StatusController::class, 'usage'])->name('ai.usage');
        Route::get('ai-workspace/plans', [StatusController::class, 'plans'])->name('ai.plans');
        Route::get('ai-workspace/subscription', [StatusController::class, 'subscription'])->name('ai.subscription');
    }
});
