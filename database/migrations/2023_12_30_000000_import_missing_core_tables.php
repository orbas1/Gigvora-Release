<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * List of legacy tables created by this migration.
     *
     * @var string[]
     */
    private array $tables = [
        'account_active_requests',
        'activities',
        'addons',
        'album_images',
        'albums',
        'batchs',
        'block_users',
        'blogcategories',
        'blogs',
        'brands',
        'categories',
        'chats',
        'comments',
        'currencies',
        'events',
        'failed_jobs',
        'feeling_and_activities',
        'followers',
        'friendships',
        'group_members',
        'groups',
        'invites',
        'live_streamings',
        'marketplaces',
        'media_files',
        'message_thrades',
        'page_likes',
        'pagecategories',
        'pages',
        'password_resets',
        'payment_gateways',
        'payment_histories',
        'personal_access_tokens',
        'post_shares',
        'posts',
        'reports',
        'saved_products',
        'saveforlaters',
        'shares',
        'sponsors',
        'stories',
        'videos',
    ];

    public function up(): void
    {
        $sql = file_get_contents(database_path('sql/missing_tables.sql'));
        if (! $sql) {
            return;
        }

        foreach (array_filter(preg_split('/;\\s*\\n/', trim($sql))) as $statement) {
            $statement = trim($statement);
            if ($statement === '') {
                continue;
            }

            DB::statement($statement . ';');
        }
    }

    public function down(): void
    {
        Schema::disableForeignKeyConstraints();

        foreach ($this->tables as $table) {
            Schema::dropIfExists($table);
        }

        Schema::enableForeignKeyConstraints();
    }
};
