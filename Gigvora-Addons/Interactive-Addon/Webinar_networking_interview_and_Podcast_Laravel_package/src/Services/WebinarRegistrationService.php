<?php

namespace Jobi\WebinarNetworkingInterviewPodcast\Services;

use Illuminate\Validation\ValidationException;
use Jobi\WebinarNetworkingInterviewPodcast\Models\Ticket;
use Jobi\WebinarNetworkingInterviewPodcast\Models\Webinar;
use Jobi\WebinarNetworkingInterviewPodcast\Models\WebinarRegistration;

class WebinarRegistrationService
{
    public function __construct(protected WebinarReminderService $reminders)
    {
    }

    public function register(Webinar $webinar, int $userId, ?int $ticketId = null): WebinarRegistration
    {
        $ticket = null;

        if ($ticketId) {
            $ticket = $webinar->tickets()->whereKey($ticketId)->first();

            if (! $ticket) {
                throw ValidationException::withMessages([
                    'ticket_id' => [__('Selected ticket tier is not valid for this webinar.')],
                ]);
            }

            if ($ticket->status !== 'available') {
                throw ValidationException::withMessages([
                    'ticket_id' => [__('Selected ticket tier is no longer available.')],
                ]);
            }
        } elseif ($webinar->is_paid && ($webinar->price ?? 0) > 0) {
            throw ValidationException::withMessages([
                'ticket_id' => [__('Ticket selection is required for paid webinars.')],
            ]);
        }

        $activeCount = $webinar->registrations()
            ->whereNotIn('status', ['cancelled'])
            ->count();

        $status = 'registered';
        $capacity = $webinar->capacity;

        if ($capacity && $activeCount >= $capacity) {
            if ($webinar->waitlist_enabled) {
                $status = 'waitlisted';
            } else {
                throw ValidationException::withMessages([
                    'webinar' => [__('This webinar is full. Join the waitlist if available.')],
                ]);
            }
        }

        $registration = WebinarRegistration::firstOrCreate(
            [
                'webinar_id' => $webinar->id,
                'user_id' => $userId,
            ],
            [
                'status' => $status,
                'ticket_id' => $ticket?->id,
            ],
        );

        if ($status === 'waitlisted' && $registration->status !== 'waitlisted') {
            $registration->forceFill(['status' => 'waitlisted'])->save();
        }

        if ($registration->status === 'registered') {
            $this->reminders->schedule($webinar, $userId);
        }

        return $registration;
    }

    public function unregister(Webinar $webinar, int $userId): void
    {
        WebinarRegistration::where('webinar_id', $webinar->id)
            ->where('user_id', $userId)
            ->delete();

        $this->reminders->cancel($webinar, $userId);
    }

    public function markAttendance(Webinar $webinar, int $userId): WebinarRegistration
    {
        return WebinarRegistration::updateOrCreate(
            [
                'webinar_id' => $webinar->id,
                'user_id' => $userId,
            ],
            [
                'status' => 'attended',
                'checked_in_at' => now(),
            ],
        );
    }
}

