<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class DisputeStageController
{
    public function stages(int $disputeId)
    {
        return response()->json([
            'data' => [
                'stages' => DB::table('dispute_stages')->where('dispute_id', $disputeId)->orderBy('id')->get(),
            ],
        ]);
    }

    public function advance(Request $request, int $disputeId)
    {
        $validator = Validator::make($request->all(), [
            'stage' => ['required', 'string', 'in:initial,mediation,partial_refund,full_refund,arbitration,closed'],
            'notes' => ['nullable', 'string'],
            'decision' => ['nullable', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $id = DB::table('dispute_stages')->insertGetId([
            'dispute_id' => $disputeId,
            'stage' => $request->string('stage'),
            'notes' => $request->string('notes'),
            'decision' => $request->string('decision'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('disputes')->where('id', $disputeId)->update([
            'status' => $request->string('stage'),
            'updated_at' => now(),
        ]);

        return response()->json([
            'message' => __('Dispute advanced'),
            'data' => DB::table('dispute_stages')->find($id),
        ], Response::HTTP_CREATED);
    }
}
