<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Route;
use Jobs\Models\InterviewSchedule;
use Jobs\Models\JobApplication;
use Jobi\WebinarNetworkingInterviewPodcast\Models\InterviewSlot;

class UtilitiesInterviewSyncService
{
    public function __construct(protected UtilitiesCalendarService $calendar)
    {
    }

    public function syncJobsInterview(InterviewSchedule $schedule): void
    {
        $schedule->loadMissing([
            'application.job.company.owner',
            'application.candidate.user',
        ]);

        $job = $schedule->application?->job;
        $company = $job?->company;
        $companyOwner = $company?->owner;
        $candidate = $schedule->application?->candidate?->user;
        $status = $this->normalizeStatus($schedule->status);
        $startsAt = $schedule->scheduled_at ?? now();

        if ($candidate instanceof User) {
            $this->calendar->upsert([
                'user_id' => $candidate->id,
                'source' => 'jobs_interview',
                'source_id' => (string) $schedule->id,
                'title' => $job?->title ?? get_phrase('Interview'),
                'subtitle' => $company?->name,
                'description' => $schedule->instructions,
                'starts_at' => $startsAt,
                'location' => $schedule->meeting_link ?: $schedule->location,
                'status' => $status,
                'metadata' => [
                    'job_id' => $job?->id,
                    'company_id' => $company?->id,
                    'company_name' => $company?->name,
                    'interview_schedule_id' => $schedule->id,
                    'type' => 'candidate',
                    'meeting_link' => $schedule->meeting_link,
                    'instructions' => $schedule->instructions,
                    'cta_url' => $this->jobCandidateUrl($job),
                    'scheduled_at' => $startsAt->toIso8601String(),
                ],
            ]);

            $this->upsertNotification([
                'sender_user_id' => $companyOwner?->id,
                'reciver_user_id' => $candidate->id,
                'type' => 'job_interview',
                'resource_type' => 'jobs_interview',
                'resource_id' => (string) $schedule->id,
                'title' => get_phrase('Interview scheduled'),
                'message' => get_phrase('Interview for :job at :company', [
                    'job' => $job?->title ?? get_phrase('a role'),
                    'company' => $company?->name ?? get_phrase('Gigvora employer'),
                ]),
                'action_url' => $this->jobCandidateUrl($job),
                'data' => [
                    'job_title' => $job?->title,
                    'company_name' => $company?->name,
                    'scheduled_at' => $startsAt->toIso8601String(),
                    'meeting_link' => $schedule->meeting_link,
                    'status' => $status,
                    'instructions' => $schedule->instructions,
                ],
            ]);
        }

        if ($companyOwner instanceof User) {
            $this->calendar->upsert([
                'user_id' => $companyOwner->id,
                'source' => 'jobs_interview',
                'source_id' => 'employer-'.$schedule->id,
                'title' => $job?->title ?? get_phrase('Interview'),
                'subtitle' => $candidate?->name,
                'description' => $schedule->instructions,
                'starts_at' => $startsAt,
                'location' => $schedule->meeting_link ?: $schedule->location,
                'status' => $status,
                'metadata' => [
                    'job_id' => $job?->id,
                    'interview_schedule_id' => $schedule->id,
                    'type' => 'employer',
                    'candidate_name' => $candidate?->name,
                    'cta_url' => $this->employerInterviewUrl(),
                    'scheduled_at' => $startsAt->toIso8601String(),
                ],
            ]);

            $this->upsertNotification([
                'sender_user_id' => $candidate?->id,
                'reciver_user_id' => $companyOwner->id,
                'type' => 'job_interview_employer',
                'resource_type' => 'jobs_interview',
                'resource_id' => (string) $schedule->id,
                'title' => get_phrase('Interview on calendar'),
                'message' => get_phrase(':candidate interview for :job', [
                    'candidate' => $candidate?->name ?? get_phrase('Candidate'),
                    'job' => $job?->title ?? get_phrase('role'),
                ]),
                'action_url' => $this->employerInterviewUrl(),
                'data' => [
                    'job_title' => $job?->title,
                    'candidate_name' => $candidate?->name,
                    'scheduled_at' => $startsAt->toIso8601String(),
                    'status' => $status,
                ],
            ]);
        }
    }

    public function deleteJobsInterview(InterviewSchedule $schedule): void
    {
        $schedule->loadMissing([
            'application.job.company.owner',
            'application.candidate.user',
        ]);

        $candidateId = $schedule->application?->candidate?->user?->id;
        $ownerId = $schedule->application?->job?->company?->owner?->id;

        if ($candidateId) {
            $this->calendar->cancel($candidateId, 'jobs_interview', (string) $schedule->id);
            $this->markNotificationCancelled($candidateId, 'jobs_interview', (string) $schedule->id);
        }

        if ($ownerId) {
            $this->calendar->cancel($ownerId, 'jobs_interview', 'employer-'.$schedule->id);
            $this->markNotificationCancelled($ownerId, 'jobs_interview', (string) $schedule->id);
        }
    }

    public function syncInteractiveSlot(InterviewSlot $slot): void
    {
        $slot->loadMissing(['interview', 'interviewer', 'interviewee']);

        $interview = $slot->interview;
        $startsAt = $slot->starts_at ?? now();
        $status = $this->normalizeStatus($slot->status);
        $link = Route::has('wnip.interviews.waiting')
            ? route('wnip.interviews.waiting', $interview)
            : null;

        if ($slot->interviewee instanceof User) {
            $this->calendar->upsert([
                'user_id' => $slot->interviewee->id,
                'source' => 'interactive_interview',
                'source_id' => 'candidate-'.$slot->id,
                'title' => $interview?->title ?? get_phrase('Interactive interview'),
                'subtitle' => $slot->metadata['role'] ?? null,
                'starts_at' => $startsAt,
                'ends_at' => $slot->ends_at,
                'location' => $slot->meeting_link,
                'status' => $status,
                'metadata' => [
                    'interview_slot_id' => $slot->id,
                    'interview_id' => $interview?->id,
                    'cta_url' => $link,
                    'meeting_link' => $slot->meeting_link,
                    'status' => $status,
                    'scheduled_at' => $startsAt->toIso8601String(),
                ],
            ]);

            $this->upsertNotification([
                'sender_user_id' => $interview?->host_id,
                'reciver_user_id' => $slot->interviewee->id,
                'type' => 'interactive_interview',
                'resource_type' => 'interactive_interview',
                'resource_id' => (string) $slot->id,
                'title' => get_phrase('Live interview reminder'),
                'message' => get_phrase('Interview starts :time', [
                    'time' => $this->formatTime($startsAt),
                ]),
                'action_url' => $link,
                'data' => [
                    'scheduled_at' => $startsAt->toIso8601String(),
                    'status' => $status,
                    'meeting_link' => $slot->meeting_link,
                    'interview_title' => $interview?->title,
                ],
            ]);
        }

        if ($slot->interviewer instanceof User) {
            $this->calendar->upsert([
                'user_id' => $slot->interviewer->id,
                'source' => 'interactive_interview',
                'source_id' => 'interviewer-'.$slot->id,
                'title' => $interview?->title ?? get_phrase('Interactive interview'),
                'subtitle' => $slot->interviewee?->name,
                'starts_at' => $startsAt,
                'ends_at' => $slot->ends_at,
                'location' => $slot->meeting_link,
                'status' => $status,
                'metadata' => [
                    'interview_slot_id' => $slot->id,
                    'interview_id' => $interview?->id,
                    'type' => 'interviewer',
                    'cta_url' => $link,
                    'scheduled_at' => $startsAt->toIso8601String(),
                ],
            ]);
        }
    }

    public function deleteInteractiveSlot(InterviewSlot $slot): void
    {
        $intervieweeId = $slot->interviewee_id;
        $interviewerId = $slot->interviewer_id;

        if ($intervieweeId) {
            $this->calendar->cancel($intervieweeId, 'interactive_interview', 'candidate-'.$slot->id);
        }

        if ($interviewerId) {
            $this->calendar->cancel($interviewerId, 'interactive_interview', 'interviewer-'.$slot->id);
        }
    }

    public function syncApplicationStatus(JobApplication $application, ?string $previousStatus = null): void
    {
        $application->loadMissing([
            'job.company.owner',
            'candidate.user',
        ]);

        $job = $application->job;
        $company = $job?->company;
        $companyOwner = $company?->owner;
        $candidate = $application->candidate?->user;
        $status = $this->formatApplicationStatus($application->status);
        $changedAt = $application->updated_at ?? now();

        if ($candidate instanceof User) {
            $this->calendar->upsert([
                'user_id' => $candidate->id,
                'source' => 'jobs_application_status',
                'source_id' => 'candidate-'.$application->id,
                'title' => $job?->title ?? get_phrase('Job application update'),
                'subtitle' => get_phrase('Status · :status', ['status' => ucfirst($status)]),
                'description' => $application->notes,
                'starts_at' => $changedAt,
                'status' => $status,
                'metadata' => [
                    'job_id' => $job?->id,
                    'application_id' => $application->id,
                    'company_name' => $company?->name,
                    'previous_status' => $previousStatus,
                    'current_status' => $application->status,
                    'note' => $application->notes,
                    'cta_url' => $this->jobCandidateUrl($job),
                ],
            ]);

            $this->upsertNotification([
                'sender_user_id' => $companyOwner?->id,
                'reciver_user_id' => $candidate->id,
                'type' => 'job_application_status',
                'resource_type' => 'jobs_application_status',
                'resource_id' => 'candidate-'.$application->id,
                'title' => get_phrase('Application status updated'),
                'message' => get_phrase(':company moved you to :status for :job', [
                    'company' => $company?->name ?? get_phrase('the employer'),
                    'status' => ucfirst($status),
                    'job' => $job?->title ?? get_phrase('the role'),
                ]),
                'action_url' => $this->jobCandidateUrl($job),
                'data' => [
                    'job_title' => $job?->title,
                    'company_name' => $company?->name,
                    'status' => $status,
                    'note' => $application->notes,
                    'previous_status' => $previousStatus,
                ],
            ]);
        }

        if ($companyOwner instanceof User) {
            $this->calendar->upsert([
                'user_id' => $companyOwner->id,
                'source' => 'jobs_application_status',
                'source_id' => 'employer-'.$application->id,
                'title' => $candidate?->name ?? get_phrase('Candidate update'),
                'subtitle' => get_phrase('Status · :status', ['status' => ucfirst($status)]),
                'description' => $application->notes,
                'starts_at' => $changedAt,
                'status' => $status,
                'metadata' => [
                    'job_id' => $job?->id,
                    'application_id' => $application->id,
                    'candidate_name' => $candidate?->name,
                    'previous_status' => $previousStatus,
                    'current_status' => $application->status,
                    'note' => $application->notes,
                    'cta_url' => $this->employerInterviewUrl(),
                ],
            ]);

            $this->upsertNotification([
                'sender_user_id' => $candidate?->id,
                'reciver_user_id' => $companyOwner->id,
                'type' => 'job_application_status_employer',
                'resource_type' => 'jobs_application_status',
                'resource_id' => 'employer-'.$application->id,
                'title' => get_phrase('Pipeline updated'),
                'message' => get_phrase(':candidate is now :status for :job', [
                    'candidate' => $candidate?->name ?? get_phrase('Candidate'),
                    'status' => ucfirst($status),
                    'job' => $job?->title ?? get_phrase('the role'),
                ]),
                'action_url' => $this->employerInterviewUrl(),
                'data' => [
                    'job_title' => $job?->title,
                    'candidate_name' => $candidate?->name,
                    'status' => $status,
                    'note' => $application->notes,
                    'previous_status' => $previousStatus,
                ],
            ]);
        }
    }

    public function deleteApplicationStatus(JobApplication $application): void
    {
        $candidateId = $application->candidate?->user?->id;
        $ownerId = $application->job?->company?->owner?->id;

        if ($candidateId) {
            $this->calendar->cancel($candidateId, 'jobs_application_status', 'candidate-'.$application->id, 'archived');
        }

        if ($ownerId) {
            $this->calendar->cancel($ownerId, 'jobs_application_status', 'employer-'.$application->id, 'archived');
        }
    }

    protected function upsertNotification(array $attributes): void
    {
        $notification = Notification::firstOrNew([
            'reciver_user_id' => $attributes['reciver_user_id'],
            'type' => $attributes['type'],
            'resource_type' => $attributes['resource_type'] ?? null,
            'resource_id' => $attributes['resource_id'] ?? null,
        ]);

        $notification->sender_user_id = $attributes['sender_user_id'] ?? null;
        $notification->title = $attributes['title'] ?? null;
        $notification->message = $attributes['message'] ?? null;
        $notification->action_url = $attributes['action_url'] ?? null;
        $notification->data = $attributes['data'] ?? [];
        $notification->status = '0';
        $notification->view = '0';
        $notification->type = $attributes['type'];
        $notification->resource_type = $attributes['resource_type'] ?? null;
        $notification->resource_id = $attributes['resource_id'] ?? null;
        $notification->save();
    }

    protected function markNotificationCancelled(int $userId, string $resourceType, string $resourceId): void
    {
        Notification::where('reciver_user_id', $userId)
            ->where('resource_type', $resourceType)
            ->where('resource_id', $resourceId)
            ->update([
                'data->status' => 'cancelled',
                'status' => '1',
            ]);
    }

    protected function normalizeStatus(?string $status): string
    {
        return match (strtolower((string) $status)) {
            'cancelled', 'canceled' => 'cancelled',
            'completed', 'done' => 'completed',
            'rescheduled', 'reschedule' => 'rescheduled',
            default => 'scheduled',
        };
    }

    protected function formatApplicationStatus(?string $status): string
    {
        $value = strtolower((string) $status);

        return match ($value) {
            'offer', 'offer_sent', 'offer_made' => 'offer',
            'hired', 'accepted' => 'hired',
            'rejected', 'declined' => 'rejected',
            'interview' => 'interview',
            'screening' => 'screening',
            'withdrawn' => 'withdrawn',
            default => $value ?: 'applied',
        };
    }

    protected function employerInterviewUrl(): ?string
    {
        if (Route::has('employer.interviews.index')) {
            return route('employer.interviews.index');
        }

        return null;
    }

    protected function jobCandidateUrl($job): ?string
    {
        if (! $job) {
            return null;
        }

        if (Route::has('jobs.show')) {
            return route('jobs.show', $job);
        }

        return url('/jobs/'.$job->id);
    }

    protected function formatTime(Carbon $date): string
    {
        return $date->clone()->timezone(config('app.timezone', 'UTC'))->format('M d, H:i');
    }
}

