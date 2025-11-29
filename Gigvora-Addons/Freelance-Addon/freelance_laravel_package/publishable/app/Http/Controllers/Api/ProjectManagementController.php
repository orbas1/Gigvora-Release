<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class ProjectManagementController
{
    public function board(string $slug)
    {
        $project = DB::table('projects')->where('slug', $slug)->first();
        if (!$project) {
            return response()->json(['message' => __('Project not found')], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'data' => [
                'project' => $project,
                'freelancers' => DB::table('project_freelancers')->where('project_id', $project->id)->get(),
                'tasks' => DB::table('project_tasks')->where('project_id', $project->id)->get(),
                'milestones' => DB::table('project_milestones')->where('project_id', $project->id)->get(),
                'invitations' => DB::table('project_invitations')->where('project_id', $project->id)->get(),
                'submissions' => DB::table('project_submissions')->where('project_id', $project->id)->get(),
                'time_logs' => DB::table('project_time_logs')->where('project_id', $project->id)->get(),
                'reviews' => DB::table('project_reviews')->where('project_id', $project->id)->get(),
            ],
        ]);
    }

    public function addTask(Request $request, string $slug)
    {
        $project = DB::table('projects')->where('slug', $slug)->first();
        if (!$project) {
            return response()->json(['message' => __('Project not found')], Response::HTTP_NOT_FOUND);
        }

        $validator = Validator::make($request->all(), [
            'title' => ['required', 'string'],
            'assignee' => ['nullable', 'string'],
            'due_date' => ['nullable', 'date'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $id = DB::table('project_tasks')->insertGetId([
            'project_id' => $project->id,
            'title' => $request->string('title'),
            'assignee' => $request->string('assignee'),
            'status' => 'pending',
            'due_date' => $request->date('due_date'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'message' => __('Task created'),
            'data' => DB::table('project_tasks')->find($id),
        ], Response::HTTP_CREATED);
    }

    public function updateTaskStatus(Request $request, int $taskId)
    {
        $validator = Validator::make($request->all(), [
            'status' => ['required', 'string', 'in:pending,in_progress,blocked,done'],
            'hours_logged' => ['nullable', 'numeric'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        DB::table('project_tasks')->where('id', $taskId)->update([
            'status' => $request->string('status'),
            'hours_logged' => $request->input('hours_logged'),
            'updated_at' => now(),
        ]);

        return response()->json([
            'message' => __('Task updated'),
            'data' => DB::table('project_tasks')->find($taskId),
        ]);
    }

    public function milestone(Request $request, string $slug)
    {
        $project = DB::table('projects')->where('slug', $slug)->first();
        if (!$project) {
            return response()->json(['message' => __('Project not found')], Response::HTTP_NOT_FOUND);
        }

        $validator = Validator::make($request->all(), [
            'title' => ['required', 'string'],
            'amount' => ['required', 'numeric'],
            'due_date' => ['nullable', 'date'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $id = DB::table('project_milestones')->insertGetId([
            'project_id' => $project->id,
            'title' => $request->string('title'),
            'amount' => $request->input('amount'),
            'status' => 'pending',
            'due_date' => $request->date('due_date'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'message' => __('Milestone created'),
            'data' => DB::table('project_milestones')->find($id),
        ], Response::HTTP_CREATED);
    }

    public function submitWork(Request $request, string $slug)
    {
        $project = DB::table('projects')->where('slug', $slug)->first();
        if (!$project) {
            return response()->json(['message' => __('Project not found')], Response::HTTP_NOT_FOUND);
        }

        $validator = Validator::make($request->all(), [
            'milestone_id' => ['nullable', 'integer'],
            'notes' => ['required', 'string'],
            'attachment_url' => ['nullable', 'url'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $id = DB::table('project_submissions')->insertGetId([
            'project_id' => $project->id,
            'milestone_id' => $request->input('milestone_id'),
            'notes' => $request->string('notes'),
            'attachment_url' => $request->string('attachment_url'),
            'status' => 'submitted',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'message' => __('Submission recorded'),
            'data' => DB::table('project_submissions')->find($id),
        ]);
    }

    public function logTime(Request $request, string $slug)
    {
        $project = DB::table('projects')->where('slug', $slug)->first();
        if (!$project) {
            return response()->json(['message' => __('Project not found')], Response::HTTP_NOT_FOUND);
        }

        $validator = Validator::make($request->all(), [
            'freelancer' => ['required', 'string'],
            'hours' => ['required', 'numeric'],
            'note' => ['nullable', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $id = DB::table('project_time_logs')->insertGetId([
            'project_id' => $project->id,
            'freelancer' => $request->string('freelancer'),
            'hours' => $request->input('hours'),
            'note' => $request->string('note'),
            'logged_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('project_tasks')->where('project_id', $project->id)->increment('hours_logged', $request->input('hours', 0));

        return response()->json([
            'message' => __('Time log created'),
            'data' => DB::table('project_time_logs')->find($id),
        ], Response::HTTP_CREATED);
    }

    public function invite(Request $request, string $slug)
    {
        $project = DB::table('projects')->where('slug', $slug)->first();
        if (!$project) {
            return response()->json(['message' => __('Project not found')], Response::HTTP_NOT_FOUND);
        }

        $validator = Validator::make($request->all(), [
            'freelancer' => ['required', 'string'],
            'message' => ['nullable', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $id = DB::table('project_invitations')->insertGetId([
            'project_id' => $project->id,
            'freelancer' => $request->string('freelancer'),
            'message' => $request->string('message'),
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'message' => __('Invitation sent'),
            'data' => DB::table('project_invitations')->find($id),
        ], Response::HTTP_CREATED);
    }

    public function matchFreelancers(Request $request, string $slug)
    {
        $project = DB::table('projects')->where('slug', $slug)->first();
        if (!$project) {
            return response()->json(['message' => __('Project not found')], Response::HTTP_NOT_FOUND);
        }

        $skills = $request->input('skills', []);
        $location = $request->input('location');

        $matches = DB::table('users')
            ->select('id', 'name', 'email', 'profile_photo_path')
            ->when(!empty($skills), function ($query) use ($skills) {
                $query->whereExists(function ($q) use ($skills) {
                    $q->selectRaw('1')
                        ->from('user_skills')
                        ->whereColumn('user_skills.user_id', 'users.id')
                        ->whereIn('user_skills.skill', $skills);
                });
            })
            ->when($location, fn($q) => $q->where('country', $location))
            ->limit(10)
            ->get();

        return response()->json([
            'data' => [
                'project' => $project,
                'matches' => $matches,
            ],
        ]);
    }

    public function review(Request $request, string $slug)
    {
        $project = DB::table('projects')->where('slug', $slug)->first();
        if (!$project) {
            return response()->json(['message' => __('Project not found')], Response::HTTP_NOT_FOUND);
        }

        $validator = Validator::make($request->all(), [
            'rating' => ['required', 'numeric', 'between:1,5'],
            'comment' => ['nullable', 'string'],
            'author' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $id = DB::table('project_reviews')->insertGetId([
            'project_id' => $project->id,
            'rating' => $request->input('rating'),
            'comment' => $request->string('comment'),
            'author' => $request->string('author'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'message' => __('Review stored'),
            'data' => DB::table('project_reviews')->find($id),
        ], Response::HTTP_CREATED);
    }
}
