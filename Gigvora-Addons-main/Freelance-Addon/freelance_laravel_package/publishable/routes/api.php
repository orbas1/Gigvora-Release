<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\GigController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\SellerController;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Api\DisputeController;
use App\Http\Controllers\Api\AccountController;
use App\Http\Controllers\Api\GeneralController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\TaxonomyController;
use App\Http\Controllers\Api\EducationController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\ProfileSettingsController;
use App\Http\Controllers\Api\ProjectManagementController;
use App\Http\Controllers\Api\GigManagementController;
use App\Http\Controllers\Api\DisputeStageController;
use App\Http\Controllers\Api\EscrowManagementController;
use App\Http\Controllers\Api\ProfileTagController;
use App\Http\Controllers\Api\ProfileEnrichmentController;
use App\Http\Controllers\OptionBuilderSettings\OptionBuilderController;
use App\Http\Controllers\Api\ProposalController;
use App\Http\Controllers\Api\SendMessageController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix(config('freelance.api.prefix', 'api'))
    ->middleware(config('freelance.api.middleware', ['api']))
    ->group(function () {
        // Auth endpoints are provided by the host app; avoid duplicating login/register here.

        // Taxonomies
        Route::get('taxonomies',    [TaxonomyController::class, 'getTaxonomies']);

        Route::get('tags',                  [TaxonomyController::class, 'getTags']);
        Route::get('gig_delivery_time',     [TaxonomyController::class, 'getGigDeliveryTime']);
        Route::get('project_durations',     [TaxonomyController::class, 'getProjectDuration']);
        Route::get('project-location',      [TaxonomyController::class, 'getProjectLocation']);
        Route::get('countries',             [TaxonomyController::class, 'getCountries']);
        Route::get('country-states/{country_id}', [TaxonomyController::class, 'getAllCountryState']);

        // Search items
        Route::get('projects',          [ProjectController::class, 'index']);
        Route::get('gigs',              [GigController::class, 'index']);
        Route::get('sellers',           [ SellerController::class, 'index']);
        Route::get('recent-projects',   [ProjectController::class, 'recentProjects']);

        Route::get('project/{id}', [ProjectController::class, 'getProjectDetail']);
        Route::get('gig/{id}',       [ GigController::class, 'gigDetail']);
        Route::get('seller/{id}',    [ SellerController::class, 'sellerDetail']);

        Route::get('popular-gigs',  [GigController::class, 'popularGigs']);
        Route::get('top-sellers',   [SellerController::class, 'topSellers']);

        Route::middleware('auth:sanctum')->group(function () {
            Route::get('resend-email',  [AuthController::class, 'resendEmail']);
            Route::post('logout',       [AuthController::class, 'logout']);
        });

        Route::middleware('auth:sanctum', 'verified')->group(function () {

            Route::middleware(['role:buyer|seller|buyer,api'])->group(function () {


                // Detail pages
                Route::get('user', [ ProfileSettingsController::class, 'useDetail']);
                Route::post('switch-profile',  [ ProfileSettingsController::class, 'switchProfile']);
                Route::post('change-password', [ ProfileSettingsController::class, 'changePassword']);

                Route::post('favourite-item',  [ GeneralController::class, 'setFavItem']);
                Route::get('saved-items',      [ GeneralController::class, 'getSavedItem']);
            });

            Route::middleware(['role:buyer|seller,api'])->group(function () {
                Route::get('portfolios',        [ SellerController::class, 'getPortfolios']);
                Route::put('portfolio/{id}',    [ SellerController::class, 'updatePortfolio']);
                Route::post('portfolio',        [ SellerController::class, 'addPortfolio']);
                Route::delete('portfolio/{id}', [ SellerController::class, 'deletePortfolio']);
            });

            Route::post('reset-password',       [AuthController::class, 'resetPassword']);
            Route::get('account-stats',         [AccountController::class, 'getAccountStats']);
            Route::get('payout-history',        [AccountController::class, 'getPayoutHistory']);
            Route::post('setup-payout-method',  [AccountController::class, 'setPayoutMethod']);
            Route::get('payout-method',         [AccountController::class, 'getPayoutMethod']);
            Route::post('withdraw-amount',      [AccountController::class, 'withdrawAmount']);
            Route::get('settings',              [OptionBuilderController::class, 'getOpSettings']);

            Route::get('educations',                [EducationController::class, 'getEducations']);
            Route::post('education',                [EducationController::class, 'addEducation']);
            Route::post('update-education/{id}',    [EducationController::class, 'updateEducation']);
            Route::delete('delete-education/{id}',  [EducationController::class, 'deleteEducation']);

            // Account settings
            Route::post('update-privacy-info',  [ ProfileSettingsController::class, 'updatePrivacyInfo']);
            Route::post('deactivate-account',   [ ProfileSettingsController::class, 'deactivateAccount']);

            Route::post('update-profile-info',  [ ProfileSettingsController::class, 'updateProfileInfo']);
            Route::post('update-profile-photo', [ ProfileSettingsController::class, 'updateProfilePhoto']);

            Route::put('identity-information',  [ ProfileSettingsController::class, 'uploadIdentityInfo']);
            Route::get('identity-information',  [ ProfileSettingsController::class, 'getIdentityInfo']);

            Route::get('billing-information',   [ ProfileSettingsController::class, 'getBillingInfo']);
            Route::post('billing-information',  [ ProfileSettingsController::class, 'updateBillingInfo']);

            Route::get('disputes', [ DisputeController::class, 'getDisputes']);
            Route::get('invoices', [ TransactionController::class, 'getInvoices']);
            Route::get('dispute/{id}/stages', [DisputeStageController::class, 'stages']);
        Route::post('dispute/{id}/advance', [DisputeStageController::class, 'advance']);

        Route::get('project/{slug}/board', [ProjectManagementController::class, 'board']);
        Route::post('project/{slug}/tasks', [ProjectManagementController::class, 'addTask']);
        Route::post('project/task/{taskId}', [ProjectManagementController::class, 'updateTaskStatus']);
        Route::post('project/{slug}/milestones', [ProjectManagementController::class, 'milestone']);
        Route::post('project/{slug}/submission', [ProjectManagementController::class, 'submitWork']);
        Route::post('project/{slug}/time-log', [ProjectManagementController::class, 'logTime']);
        Route::post('project/{slug}/invite', [ProjectManagementController::class, 'invite']);
        Route::post('project/{slug}/match', [ProjectManagementController::class, 'matchFreelancers']);
        Route::post('project/{slug}/review', [ProjectManagementController::class, 'review']);

        Route::get('freelance/tags', [ProfileTagController::class, 'index']);
        Route::post('freelance/profile/tags', [ProfileTagController::class, 'saveUserTags']);
        Route::post('gig/{id}/tags', [ProfileTagController::class, 'saveGigTags']);

        Route::get('profile/portfolios', [ProfileEnrichmentController::class, 'portfolios']);
        Route::post('profile/portfolio', [ProfileEnrichmentController::class, 'storePortfolio']);
        Route::put('profile/portfolio/{id}', [ProfileEnrichmentController::class, 'updatePortfolio']);
        Route::delete('profile/portfolio/{id}', [ProfileEnrichmentController::class, 'deletePortfolio']);

        Route::get('profile/educations', [ProfileEnrichmentController::class, 'educations']);
        Route::post('profile/education', [ProfileEnrichmentController::class, 'storeEducation']);
        Route::put('profile/education/{id}', [ProfileEnrichmentController::class, 'updateEducation']);
        Route::delete('profile/education/{id}', [ProfileEnrichmentController::class, 'deleteEducation']);

        Route::get('profile/certifications', [ProfileEnrichmentController::class, 'certifications']);
        Route::post('profile/certification', [ProfileEnrichmentController::class, 'storeCertification']);
        Route::put('profile/certification/{id}', [ProfileEnrichmentController::class, 'updateCertification']);
        Route::delete('profile/certification/{id}', [ProfileEnrichmentController::class, 'deleteCertification']);

        Route::get('profile/reviews', [ProfileEnrichmentController::class, 'reviews']);
        Route::post('profile/review', [ProfileEnrichmentController::class, 'storeReview']);

        Route::get('gig/{id}/management', [GigManagementController::class, 'overview']);
        Route::post('gig/{id}/timeline', [GigManagementController::class, 'addTimeline']);
        Route::post('gig/{id}/faq', [GigManagementController::class, 'addFaq']);
        Route::post('gig/{id}/addon', [GigManagementController::class, 'addAddon']);
        Route::post('gig/{id}/package', [GigManagementController::class, 'addPackage']);
        Route::post('gig/{id}/requirement', [GigManagementController::class, 'requirement']);
        Route::post('gig/{id}/change', [GigManagementController::class, 'change']);
        Route::post('gig/{id}/review', [GigManagementController::class, 'review']);
        Route::post('gigs/custom', [GigManagementController::class, 'customGig']);

        Route::get('fee-tax',        [ProposalController::class, 'getFeeTax'] );
        Route::post('send-message',  [SendMessageController::class, 'sendMessage'] );
        Route::middleware(['role:seller,api'])->group(function () {
            Route::post('submit-proposal/{id}',  [ProposalController::class, 'submitProposal']);
        });

        Route::get('escrows/manage', [EscrowManagementController::class, 'index']);
        Route::post('escrow/{id}/partial-release', [EscrowManagementController::class, 'partialRelease']);
        Route::post('escrow/{id}/decision', [EscrowManagementController::class, 'adminDecision']);

        Route::middleware(['role:admin,api'])->group(function () {
            Route::post('admin/freelance/tags', [ProfileTagController::class, 'store']);
            Route::put('admin/freelance/tags/{id}', [ProfileTagController::class, 'update']);
            Route::delete('admin/freelance/tags/{id}', [ProfileTagController::class, 'destroy']);
            Route::get('admin/profile/{userId}/enrichment', [ProfileEnrichmentController::class, 'adminOverview']);
            Route::delete('admin/profile/review/{id}', [ProfileEnrichmentController::class, 'adminDeleteReview']);
        });
    });

    Route::fallback(function () {
        return response()->message(message: __('messages.api_url_not_found'), status_code: Response::HTTP_NOT_FOUND);
    });
});

