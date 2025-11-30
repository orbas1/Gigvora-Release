<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\FreelanceWorkspaceService;
use Illuminate\Http\Request;

class FreelanceWorkspaceController extends Controller
{
    public function __invoke(Request $request, FreelanceWorkspaceService $workspace)
    {
        abort_unless(freelanceEnabled(), 404);

        $snapshot = $workspace->snapshotForUser($request->user());

        return response()->json(['data' => $snapshot]);
    }
}


