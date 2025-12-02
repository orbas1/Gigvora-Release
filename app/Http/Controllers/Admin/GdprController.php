<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\GdprService;
use Illuminate\Http\Request;

class GdprController extends Controller
{
    public function __construct(private GdprService $gdprService)
    {
    }

    public function export(User $user)
    {
        $payload = $this->gdprService->exportUser($user);
        $this->gdprService->logExport($user);

        return response()->json($payload);
    }

    public function erase(Request $request, User $user)
    {
        $request->validate([
            'confirm' => ['required', 'boolean'],
        ]);

        if (!$request->boolean('confirm')) {
            return response()->json(['message' => 'Erasure not confirmed'], 422);
        }

        $this->gdprService->eraseUser($user);

        return response()->json(['message' => 'User data erased and anonymized']);
    }
}

