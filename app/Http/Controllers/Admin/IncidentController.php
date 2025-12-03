<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class IncidentController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'severity' => ['nullable', 'string', 'max:32'],
        ]);

        $log = AuditLog::create([
            'actor_id' => $request->user()?->id,
            'target_type' => 'incident',
            'target_id' => Str::uuid()->toString(),
            'action' => 'incident.report',
            'changes' => [
                'type' => $validated['type'],
                'description' => $validated['description'],
                'severity' => $validated['severity'] ?? 'info',
            ],
            'source' => 'admin',
        ]);

        return response()->json($log, 201);
    }
}

