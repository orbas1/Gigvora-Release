<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\FreelanceWorkspaceService;
use App\Support\Authorization\PermissionMatrix;
use Illuminate\Http\Request;

class FreelanceWorkspaceController extends Controller
{
    public function __invoke(Request $request, FreelanceWorkspaceService $workspace, PermissionMatrix $permissions)
    {
        abort_unless(freelanceEnabled(), 404);
        abort_unless($permissions->allowed($request->user(), 'freelance.workspace.access'), 403);

        $snapshot = $workspace->snapshotForUser($request->user());

        return response()->json(['data' => $snapshot]);
    }
}


