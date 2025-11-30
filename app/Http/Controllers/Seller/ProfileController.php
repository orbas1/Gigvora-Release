<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ChatController;
use App\Models\Profile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index(string $slug)
    {
        $profile = Profile::with(['skills', 'portfolio', 'gigs' => fn ($query) => $query->latest()])->where('slug', $slug)->firstOrFail();

        return view('freelance::profile.show', compact('profile'));
    }

    public function sendMessage(Request $request): RedirectResponse
    {
        $request->validate([
            'recipient_id' => 'required|integer|exists:users,id',
            'message' => 'required|string|max:5000',
        ]);

        $chatRequest = Request::create('', 'POST', array_merge($request->only('message'), [
            'reciver_id' => $request->input('recipient_id'),
            'messagecenter' => 'freelance',
            'thumbsup' => null,
            'multiple_files' => [],
        ]));

        app(ChatController::class)->chat_save($chatRequest);

        return back()->with('status', __('Message sent to freelancer.'));
    }
}

