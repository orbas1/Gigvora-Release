<?php

namespace Jobs\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Jobs\Models\JobApplication;
use Jobs\Models\ScreeningAnswer;
use Jobs\Models\ScreeningQuestion;
use Jobs\Support\Analytics\JobsAnalytics;

class ScreeningController extends Controller
{
    public function questions($jobId)
    {
        return response()->json(ScreeningQuestion::where('job_id', $jobId)->get());
    }

    public function storeQuestion(Request $request, $jobId)
    {
        $data = $request->validate([
            'question' => ['required', 'string'],
            'type' => ['required', 'string'],
            'options' => ['nullable', 'array'],
            'is_required' => ['boolean'],
        ]);

        $question = ScreeningQuestion::create(array_merge($data, ['job_id' => $jobId]));

        return response()->json($question, 201);
    }

    public function answer(Request $request, JobApplication $jobApplication)
    {
        $answers = $request->validate([
            'answers' => ['required', 'array'],
            'answers.*.screening_question_id' => ['required', 'integer'],
            'answers.*.answer' => ['required'],
        ]);

        $created = collect($answers['answers'])->map(function ($answer) use ($jobApplication) {
            return ScreeningAnswer::create([
                'job_application_id' => $jobApplication->id,
                'screening_question_id' => $answer['screening_question_id'],
                'answer' => $answer['answer'],
            ]);
        });

        $jobApplication->update(['screening_score' => $created->count()]);

        JobsAnalytics::dispatch('screening_questions_answered', [
            'application_id' => $jobApplication->id,
            'count' => $created->count(),
        ]);

        return response()->json($created);
    }
}
