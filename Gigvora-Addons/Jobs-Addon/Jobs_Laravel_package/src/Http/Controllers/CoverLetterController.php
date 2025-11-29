<?php

namespace Jobs\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Jobs\Models\CoverLetter;
use Jobs\Support\Analytics\JobsAnalytics;

class CoverLetterController extends Controller
{
    public function index(Request $request)
    {
        $candidateId = $request->user()?->id ?? $request->integer('candidate_id');
        $query = CoverLetter::query();

        if ($candidateId) {
            $query->where('candidate_id', $candidateId);
        }

        return response()->json($query->get());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'candidate_id' => ['required', 'integer'],
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string'],
        ]);

        $letter = CoverLetter::create($data);

        JobsAnalytics::dispatch('cover_letter_created', [
            'cover_letter_id' => $letter->id,
            'candidate_id' => $letter->candidate_id,
        ]);

        return response()->json($letter, 201);
    }

    public function show(CoverLetter $coverLetter)
    {
        return response()->json(['data' => $coverLetter]);
    }

    public function update(Request $request, CoverLetter $coverLetter)
    {
        $data = $request->validate([
            'title' => ['sometimes', 'string', 'max:255'],
            'body' => ['sometimes', 'string'],
        ]);

        $coverLetter->update($data);

        return response()->json(['data' => $coverLetter]);
    }

    public function destroy(CoverLetter $coverLetter)
    {
        $coverLetter->delete();

        return response()->json(['message' => 'Cover letter removed']);
    }
}
