<?php

namespace Jobs\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Jobs\Models\AtsStage;
use Jobs\Models\AtsStageAssignment;
use Jobs\Models\AtsPipeline;
use Jobs\Models\Job;
use Jobs\Models\JobApplication;

class AtsController extends Controller
{
    public function stages()
    {
        return response()->json(AtsStage::orderBy('position')->get());
    }

    public function move(Request $request, JobApplication $jobApplication)
    {
        $data = $request->validate([
            'ats_stage_id' => ['nullable', 'integer', 'exists:ats_stages,id'],
            'stage_id' => ['nullable', 'integer', 'exists:ats_stages,id'],
            'notes' => ['nullable', 'string'],
        ]);

        $stageId = $data['ats_stage_id'] ?? $data['stage_id'];

        if ($stageId === null) {
            abort(422, 'An ATS stage id is required.');
        }

        $assignment = AtsStageAssignment::create([
            'job_application_id' => $jobApplication->id,
            'ats_stage_id' => $stageId,
            'notes' => $data['notes'] ?? null,
            'moved_by' => $request->user()?->id,
            'moved_at' => now(),
        ]);

        $jobApplication->update(['status' => 'in-pipeline']);

        return response()->json($assignment->load('stage'));
    }

    public function pipeline(Job $job)
    {
        $pipeline = AtsPipeline::firstOrCreate(
            ['company_id' => $job->company_id, 'is_default' => true],
            ['name' => 'Default Pipeline']
        );

        if (! $pipeline->stages()->exists()) {
            $defaults = [
                ['name' => 'Applied', 'position' => 1],
                ['name' => 'Screening', 'position' => 2],
                ['name' => 'Interview', 'position' => 3],
                ['name' => 'Offer', 'position' => 4],
            ];

            foreach ($defaults as $stage) {
                $pipeline->stages()->create($stage);
            }
        }

        return response()->json([
            'id' => $pipeline->id,
            'stages' => $pipeline->stages()->orderBy('position')->get(),
        ]);
    }
}
