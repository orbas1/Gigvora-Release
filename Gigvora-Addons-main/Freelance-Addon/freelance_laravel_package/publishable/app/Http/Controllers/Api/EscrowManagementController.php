<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class EscrowManagementController
{
    public function index()
    {
        return response()->json([
            'data' => DB::table('escrows')->get()->map(function ($escrow) {
                $escrow->actions = DB::table('escrow_actions')->where('escrow_id', $escrow->id)->orderBy('created_at', 'desc')->get();
                return $escrow;
            }),
        ]);
    }

    public function partialRelease(Request $request, int $escrowId)
    {
        $validator = Validator::make($request->all(), [
            'amount' => ['required', 'numeric', 'min:0'],
            'released_by' => ['required', 'string'],
            'notes' => ['nullable', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $actionId = DB::table('escrow_actions')->insertGetId([
            'escrow_id' => $escrowId,
            'type' => 'partial_release',
            'amount' => $request->input('amount'),
            'actor' => $request->string('released_by'),
            'notes' => $request->string('notes'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('escrows')->where('id', $escrowId)->increment('released_amount', $request->input('amount'));

        return response()->json([
            'message' => __('Partial release recorded'),
            'data' => DB::table('escrow_actions')->find($actionId),
        ], Response::HTTP_CREATED);
    }

    public function adminDecision(Request $request, int $escrowId)
    {
        $validator = Validator::make($request->all(), [
            'decision' => ['required', 'string'],
            'notes' => ['nullable', 'string'],
            'admin' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $actionId = DB::table('escrow_actions')->insertGetId([
            'escrow_id' => $escrowId,
            'type' => 'admin_management',
            'decision' => $request->string('decision'),
            'actor' => $request->string('admin'),
            'notes' => $request->string('notes'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'message' => __('Escrow decision captured'),
            'data' => DB::table('escrow_actions')->find($actionId),
        ], Response::HTTP_CREATED);
    }
}
