<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\FavouriteItem;
use App\Models\Role;
use App\Models\Profile;
use App\Support\Analytics\AnalyticsEventPublisher;
use App\Support\Authorization\AuditLogger;
use App\Support\Authorization\PermissionMatrix;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SiteController extends Controller
{
    public function __construct(
        protected AnalyticsEventPublisher $analytics,
        protected AuditLogger $audit,
        protected PermissionMatrix $permissions
    ) {
    }

    public function favoriteGig(int $gigId): RedirectResponse
    {
        return $this->toggleFavourite($gigId, 'gig');
    }

    public function favoriteProject(int $projectId): RedirectResponse
    {
        return $this->toggleFavourite($projectId, 'project');
    }

    public function switchRole(Request $request): RedirectResponse
    {
        $request->validate([
            'role' => 'required|in:buyer,seller',
        ]);

        $meta = getUserRole();
        $profileId = data_get($meta, 'profileId');
        $this->guardPermission($request, 'freelance.workspace.access');

        $profile = Profile::findOrFail($profileId);
        $role = Role::where('name', $request->input('role'))->firstOrFail();
        $profile->update(['role_id' => $role->id]);

        $this->audit->log(
            $request->user(),
            'freelance.role.changed',
            Profile::class,
            $profile->id,
            ['role' => $role->name]
        );

        $this->analytics->publish('freelance', 'role_switched', [
            'role' => $role->name,
            'profile_id' => $profile->id,
        ], $request->user());

        return back()->with('status', __('Freelance role updated.'));
    }

    public function processPayment(Request $request, string $gateway): RedirectResponse
    {
        $paymentData = session('payment_data');
        session()->forget('payment_data');

        return redirect()
            ->route('freelance.checkout')
            ->with('status', __('Payment processing via :gateway is not yet connected.', ['gateway' => ucfirst($gateway)]))
            ->with('payment_data', $paymentData);
    }

    protected function toggleFavourite(int $resourceId, string $type): RedirectResponse
    {
        $meta = getUserRole();
        $profileId = data_get($meta, 'profileId');

        abort_unless($profileId, 403);
        $this->guardPermission($request, 'freelance.favourites.toggle');

        $favourite = FavouriteItem::where([
            'user_id' => $profileId,
            'corresponding_id' => $resourceId,
            'type' => $type,
        ])->first();

        if ($favourite) {
            $favourite->delete();
            $action = 'removed';
        } else {
            FavouriteItem::create([
                'user_id' => $profileId,
                'corresponding_id' => $resourceId,
                'type' => $type,
            ]);
            $action = 'added';
        }

        $this->analytics->publish('freelance', 'favourite_toggled', [
            'resource_type' => $type,
            'resource_id' => $resourceId,
            'action' => $action,
        ], $request->user());

        return back()->with('status', __('Favorites updated.'));
    }

    protected function guardPermission(Request $request, string $permission): void
    {
        if (! $this->permissions->allowed($request->user(), $permission)) {
            abort(403, 'You are not authorised to perform this action.');
        }
    }
}

