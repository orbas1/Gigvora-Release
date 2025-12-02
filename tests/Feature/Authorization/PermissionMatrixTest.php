<?php

namespace Tests\Feature\Authorization;

use App\Models\AuditLog;
use App\Models\User;
use App\Support\Authorization\AuditLogger;
use App\Support\Authorization\PermissionMatrix;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class PermissionMatrixTest extends TestCase
{
    use RefreshDatabase;

    public function test_roles_are_restricted_by_matrix(): void
    {
        $matrix = new PermissionMatrix(config('permission_matrix'));

        $recruiter = User::factory()->create(['user_role' => 'recruiter']);
        $member = User::factory()->create(['user_role' => 'member']);

        $this->assertTrue($matrix->allowed($recruiter, 'jobs.create_posting'));
        $this->assertFalse($matrix->allowed($member, 'jobs.manage_applicants'));
    }

    public function test_permission_middleware_blocks_unauthorised_users(): void
    {
        $matrix = new PermissionMatrix(config('permission_matrix'));
        app()->instance(PermissionMatrix::class, $matrix);

        Route::middleware(['permission:jobs.create_posting'])->get('/permission-check', fn () => 'ok');

        $this->actingAs(User::factory()->create(['user_role' => 'member']))
            ->get('/permission-check')
            ->assertForbidden();

        $this->actingAs(User::factory()->create(['user_role' => 'recruiter']))
            ->get('/permission-check')
            ->assertOk();
    }

    public function test_audit_logger_records_role_changes(): void
    {
        $user = User::factory()->create(['user_role' => 'platform_admin']);
        $logger = app(AuditLogger::class);

        $log = $logger->log($user, 'freelance.role.changed', User::class, $user->id, ['role' => 'recruiter']);

        $this->assertInstanceOf(AuditLog::class, $log);
        $this->assertDatabaseHas('audit_logs', [
            'id' => $log->id,
            'actor_id' => $user->id,
            'action' => 'freelance.role.changed',
        ]);
    }
}

