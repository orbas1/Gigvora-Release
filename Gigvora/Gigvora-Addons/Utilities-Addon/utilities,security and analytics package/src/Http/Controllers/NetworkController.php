<?php

namespace ProNetwork\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use ProNetwork\Services\ConnectionService;

class NetworkController extends Controller
{
    public function __construct(protected ConnectionService $connections)
    {
    }

    public function index(Request $request)
    {
        $userId = $request->user()->id;
        return response()->json($this->connections->network($userId));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'connection_id' => 'required|integer',
            'degree' => 'nullable|integer|min:1|max:3',
        ]);
        $connection = $this->connections->addConnection($request->user()->id, $data['connection_id'], $data['degree'] ?? 1);
        return response()->json($connection, 201);
    }
}
