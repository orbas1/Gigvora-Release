<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\FavouriteItem;
use App\Models\Role;
use App\Models\Profile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SiteController extends Controller
{
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

        $profile = Profile::findOrFail($profileId);
        $role = Role::where('name', $request->input('role'))->firstOrFail();
        $profile->update(['role_id' => $role->id]);

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

        $favourite = FavouriteItem::where([
            'user_id' => $profileId,
            'corresponding_id' => $resourceId,
            'type' => $type,
        ])->first();

        if ($favourite) {
            $favourite->delete();
        } else {
            FavouriteItem::create([
                'user_id' => $profileId,
                'corresponding_id' => $resourceId,
                'type' => $type,
            ]);
        }

        return back()->with('status', __('Favorites updated.'));
    }
}

