<?php

namespace ProNetwork\Http\Controllers;

use Illuminate\Routing\Controller;
use ProNetwork\Http\Requests\UpdateProfessionalProfileRequest;
use ProNetwork\Services\ProfileService;

class ProfileController extends Controller
{
    public function __construct(protected ProfileService $service)
    {
    }

    public function update(UpdateProfessionalProfileRequest $request)
    {
        $profile = $this->service->updateProfile($request->user()->id, $request->validated());
        return response()->json($profile);
    }
}
