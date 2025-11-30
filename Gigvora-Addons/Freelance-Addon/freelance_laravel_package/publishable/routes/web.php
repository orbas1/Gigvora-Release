<?php

use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Gig\GigCartController;
use App\Http\Controllers\Gig\GigDetailController;
use App\Http\Controllers\SearchItem\SearchItemController;
use App\Http\Controllers\Seller\ProfileController;
use App\Http\Controllers\Site\SiteController;
use App\Http\Controllers\Webhook\WebhookController;
use App\Http\Livewire\Components\Checkout;
use App\Http\Livewire\Earnings\InvoiceDetail;
use App\Http\Livewire\Earnings\Invoices;
use App\Http\Livewire\FavouriteItems\FavouriteItems;
use App\Http\Livewire\Gig\GigCreation;
use App\Http\Livewire\Gig\GigList;
use App\Http\Livewire\Gig\GigOrders;
use App\Http\Livewire\Packages\Packages;
use App\Http\Livewire\ProfileSettings\ProfileSettings;
use App\Http\Livewire\Project\DisputeDetail;
use App\Http\Livewire\Project\DisputeList;
use App\Http\Livewire\Project\ProjectActivity;
use App\Http\Livewire\Project\ProjectCreation;
use App\Http\Livewire\Project\ProjectDetail;
use App\Http\Livewire\Project\ProjectListing;
use App\Http\Livewire\Project\ProjectProposals;
use App\Http\Livewire\Proposal\ProposalDetail;
use App\Http\Livewire\Proposal\ProposalSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

$webMiddleware = config('freelance.web.middleware', ['web']);
$webPrefix = trim(config('freelance.web.prefix', 'freelance'), '/');

Route::middleware($webMiddleware)
    ->prefix($webPrefix === '' ? '' : $webPrefix)
    ->as('freelance.')
    ->group(function (): void {
        Route::post('switch-lang', function (Request $request) {
            $locale = $request->get('tk-locale');
            if (in_array($locale, config('app.available_locales', []), true)) {
                app()->setLocale($locale);
                session()->put('locale', $locale);
            }

            return redirect()->back();
        })->name('locale.switch');

        Route::get('clear-cache', function () {
            clearCache();

            return redirect()->back();
        })->name('cache.clear');

        Route::middleware(['auth', 'role:buyer', 'verified'])->group(function (): void {
            Route::get('create-project', ProjectCreation::class)
                ->middleware('module-enabled:projects')
                ->name('buyer.projects.create');

            Route::get('project/{slug}/proposals', ProjectProposals::class)
                ->middleware('module-enabled:projects')
                ->name('buyer.projects.proposals');

            Route::get('gig-cart/{slug}', [GigCartController::class, 'index'])
                ->middleware('module-enabled:gigs')
                ->name('buyer.gig-cart');

            Route::post('favorite-gig/{id}', [SiteController::class, 'favoriteGig'])
                ->name('buyer.favorite-gig');
        });

        Route::middleware(['auth', 'role:seller', 'verified'])->group(function (): void {
            Route::get('{slug}/submit-proposal', ProposalSubmission::class)
                ->middleware('module-enabled:projects')
                ->name('seller.proposals.submit');

            Route::get('create-gig', GigCreation::class)
                ->middleware('module-enabled:gigs')
                ->name('seller.gigs.create');

            Route::get('gigs-listing', GigList::class)
                ->middleware('module-enabled:gigs')
                ->name('seller.gigs.list');

            Route::post('favorite-project/{id}', [SiteController::class, 'favoriteProject'])
                ->middleware('module-enabled:projects')
                ->name('seller.favorite-project');
        });

        Route::middleware(['auth', 'role:buyer|seller', 'verified'])->group(function (): void {
            Route::get('dashboard', [DashboardController::class, 'index'])
                ->name('dashboard');

            Route::get('project-activity/{slug}', ProjectActivity::class)
                ->middleware('module-enabled:projects')
                ->name('projects.activity');

            Route::get('settings', ProfileSettings::class)
                ->name('settings');

            Route::get('projects', ProjectListing::class)
                ->middleware('module-enabled:projects')
                ->name('projects.index');

            Route::get('dispute-detail', DisputeDetail::class)
                ->name('disputes.show');

            Route::get('dispute-list', DisputeList::class)
                ->name('disputes.index');

            Route::get('invoice-detail', InvoiceDetail::class)
                ->name('invoices.show');

            Route::get('invoices', Invoices::class)
                ->name('invoices.index');

            Route::get('packages', Packages::class)
                ->middleware('module-enabled:packages')
                ->name('packages.index');

            Route::get('checkout', Checkout::class)
                ->name('checkout');

            Route::get('favourite-items', FavouriteItems::class)
                ->name('favorites.index');

            Route::post('send-message', [ProfileController::class, 'sendMessage'])
                ->name('messages.send');

            Route::post('switch-role', [SiteController::class, 'switchRole'])
                ->name('account.switch-role');

            Route::get('gig-activity/{slug}', [App\Http\Controllers\Gig\GigActivityController::class, 'index'])
                ->middleware('module-enabled:gigs')
                ->name('gigs.activity');

            Route::post('gig-order-complete', [App\Http\Controllers\Gig\GigActivityController::class, 'GigOrderCompletion'])
                ->middleware('module-enabled:gigs');

            Route::post('gig-order-dispute', [App\Http\Controllers\Gig\GigActivityController::class, 'GigOrderDispute'])
                ->middleware('module-enabled:gigs');

            Route::get('raise-admin-dispute/{id}', [App\Http\Controllers\Gig\GigActivityController::class, 'RaiseDisputeToAdmin'])
                ->middleware('module-enabled:gigs')
                ->name('gigs.raise-dispute');

            Route::get('gig-orders', GigOrders::class)
                ->middleware('module-enabled:gigs')
                ->name('gigs.orders');

            Route::get('checkout/cancel', fn () => redirect()->route('freelance.invoices.index')->with('payment_cancel', __('general.payment_cancelled_desc')))
                ->name('checkout.cancel');

            Route::get('invoice/cancel', fn () => redirect()->route('freelance.invoices.index')->with('payment_cancel', __('general.payment_cancelled_desc')))
                ->name('invoices.cancel');

            Route::match(['get', 'post'], 'payment/success', [Checkout::class, 'success'])
                ->name('payments.success');
        });

        Route::get('project/{slug}/proposal-detail/{id}', ProposalDetail::class)
            ->middleware(['auth', 'verified', 'module-enabled:projects'])
            ->name('projects.proposals.detail');

        Route::post('favourite-item', [GigDetailController::class, 'favouriteItem'])
            ->name('favorites.store');

        Route::post('escrow-transaction-updates', [WebhookController::class, 'EscrowTransactionUpdates'])
            ->name('webhooks.escrow');

        Route::get('search-projects', [SearchItemController::class, 'searchProjects'])
            ->middleware('module-enabled:projects')
            ->name('search.projects');

        Route::get('search-sellers', [SearchItemController::class, 'searchSellers'])
            ->name('search.sellers');

        Route::get('search-gigs', [SearchItemController::class, 'index'])
            ->middleware('module-enabled:gigs')
            ->name('search.gigs');

        Route::get('project/{slug}', ProjectDetail::class)
            ->middleware('module-enabled:projects')
            ->name('projects.detail');

        Route::get('seller/{slug}', [ProfileController::class, 'index'])
            ->name('sellers.profile');

        Route::get('{gateway}/process/payment', [SiteController::class, 'processPayment'])
            ->middleware('verify-payment-gateway')
            ->name('payments.process');

        Route::get('gig-detail/{slug}', [GigDetailController::class, 'index'])
            ->middleware('module-enabled:gigs')
            ->name('gigs.detail');

        Route::post('payfast/webhook', [Checkout::class, 'payfastWebhook'])
            ->name('webhooks.payfast');

        require __DIR__.'/admin.php';
    });
