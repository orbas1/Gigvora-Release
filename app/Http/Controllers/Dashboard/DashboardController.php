<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\FreelanceWorkspaceService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(protected FreelanceWorkspaceService $workspace)
    {
    }

    public function index(Request $request)
    {
        abort_unless(freelanceEnabled(), 404);

        $meta = getUserRole();
        $profileId = data_get($meta, 'profileId');
        $roleName = data_get($meta, 'roleName');

        if (! $profileId) {
            abort(403, 'Complete your freelance profile to access the dashboard.');
        }

        $sellerRole = config('freelance.roles.seller', 'seller');

        $snapshots = $this->workspace->snapshotForProfile($profileId);

        if ($roleName === $sellerRole) {
            return view('freelance::freelancer.dashboard', $snapshots['freelancer'] ?? []);
        }

        return view('freelance::client.dashboard', $snapshots['client'] ?? []);
    }
}

