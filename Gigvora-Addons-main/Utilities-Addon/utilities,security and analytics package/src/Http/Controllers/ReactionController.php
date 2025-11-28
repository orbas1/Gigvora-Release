<?php

namespace ProNetwork\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use ProNetwork\Services\ReactionService;

class ReactionController extends Controller
{
    public function __construct(protected ReactionService $service)
    {
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'reactable_type' => 'required|string',
            'reactable_id' => 'required|integer',
            'type' => 'required|string',
        ]);
        $modelClass = $data['reactable_type'];
        $model = $modelClass::findOrFail($data['reactable_id']);
        $reaction = $this->service->react($model, $request->user()->id, $data['type']);
        return response()->json($reaction, 201);
    }
}
