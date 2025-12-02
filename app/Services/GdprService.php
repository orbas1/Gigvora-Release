<?php

namespace App\Services;

use App\Models\AuditLog;
use App\Models\Live_streamings;
use App\Models\Media_files;
use App\Models\Notification;
use App\Models\Posts;
use App\Models\Stories;
use App\Models\UtilitiesCalendarEvent;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class GdprService
{
    public function exportUser(User $user): array
    {
        return [
            'user' => $user->only([
                'id',
                'name',
                'email',
                'user_role',
                'user_name',
                'nickname',
                'username',
                'profession',
                'job',
                'marital_status',
                'phone',
                'date_of_birth',
                'status',
                'profile_status',
                'shadow_banned_until',
                'banned_reason',
                'created_at',
                'updated_at',
            ]),
            'posts' => Posts::where('user_id', $user->id)
                ->with('media_files')
                ->orderByDesc('created_at')
                ->get(),
            'stories' => Stories::where('user_id', $user->id)->orderByDesc('created_at')->get(),
            'media_files' => Media_files::where('user_id', $user->id)->orderByDesc('created_at')->get(),
            'live_streamings' => Live_streamings::where('user_id', $user->id)->orderByDesc('created_at')->get(),
            'notifications' => Notification::where(function ($query) use ($user) {
                $query->where('sender_user_id', $user->id)
                    ->orWhere('reciver_user_id', $user->id);
            })->orderByDesc('created_at')->get(),
            'calendar_events' => UtilitiesCalendarEvent::where('user_id', $user->id)->orderByDesc('created_at')->get(),
            'audit_logs' => AuditLog::where('actor_id', $user->id)
                ->orWhere(function ($query) use ($user) {
                    $query->where('target_type', User::class)
                        ->where('target_id', $user->id);
                })
                ->orderByDesc('created_at')
                ->get(),
        ];
    }

    public function eraseUser(User $user): void
    {
        Posts::where('user_id', $user->id)->delete();
        Stories::where('user_id', $user->id)->delete();
        Media_files::where('user_id', $user->id)->delete();
        Live_streamings::where('user_id', $user->id)->delete();
        Notification::where('sender_user_id', $user->id)
            ->orWhere('reciver_user_id', $user->id)
            ->delete();
        UtilitiesCalendarEvent::where('user_id', $user->id)->delete();

        $anonymizedEmail = 'deleted+' . $user->id . '@example.invalid';
        $user->update([
            'name' => 'Deleted User ' . $user->id,
            'email' => $anonymizedEmail,
            'password' => bcrypt(Str::random(32)),
            'about' => null,
            'phone' => null,
            'address' => null,
            'profile_lock_reason' => 'gdpr_erasure',
            'status' => 'deleted',
            'profile_status' => 'deleted',
            'moderation_strikes' => 0,
            'shadow_banned_until' => null,
            'banned_reason' => null,
        ]);

        DB::transaction(function () use ($user) {
            AuditLog::create([
                'actor_id' => auth()->id(),
                'target_type' => User::class,
                'target_id' => $user->id,
                'action' => 'gdpr.erase',
                'changes' => [
                    'status' => 'deleted',
                    'email' => $user->email,
                ],
                'source' => 'admin',
            ]);
        });
    }

    public function logExport(User $user): void
    {
        AuditLog::create([
            'actor_id' => auth()->id(),
            'target_type' => User::class,
            'target_id' => $user->id,
            'action' => 'gdpr.export',
            'changes' => ['exported_at' => now()->toIso8601String()],
            'source' => 'admin',
        ]);
    }
}

