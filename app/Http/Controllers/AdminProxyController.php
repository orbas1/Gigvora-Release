<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminProxyController extends Controller
{
    public function __invoke(Request $request, string $path = null)
    {
        $proxiedPath = trim('admin/' . ltrim($path ?? '', '/'), '/');

        $forward = Request::create(
            '/' . $proxiedPath,
            $request->method(),
            $request->all(),
            $request->cookies->all(),
            $request->allFiles(),
            $request->server->all()
        );

        $forward->headers->set('X-Gigvora-Admin-Forward', '1');

        return app()->handle($forward);
    }
}
