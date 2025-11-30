<?php

namespace App\Http\Controllers\Webhook;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function EscrowTransactionUpdates(Request $request)
    {
        Log::info('Escrow webhook payload', $request->all());

        return response()->json(['status' => 'received']);
    }
}

