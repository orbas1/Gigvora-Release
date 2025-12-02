<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $query = AuditLog::query()->orderByDesc('created_at');

        if ($request->filled('action')) {
            $query->where('action', $request->string('action'));
        }

        if ($request->filled('actor_id')) {
            $query->where('actor_id', $request->integer('actor_id'));
        }

        if ($request->filled('target_type')) {
            $query->where('target_type', $request->string('target_type'));
        }

        if ($request->filled('since')) {
            $query->where('created_at', '>=', $request->date('since'));
        }

        $logs = $query->paginate(50);

        return response()->json($logs);
    }
}

