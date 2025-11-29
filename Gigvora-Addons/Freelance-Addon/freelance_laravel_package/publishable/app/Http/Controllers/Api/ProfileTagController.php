<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class ProfileTagController
{
    public function index(Request $request)
    {
        $type = $request->get('type');
        $tags = DB::table('freelance_tags')
            ->when($type, fn ($query) => $query->where('type', $type))
            ->orderBy('name')
            ->get();

        return response()->json([
            'data' => [
                'tags' => $tags,
            ],
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string'],
            'slug' => ['required', 'string'],
            'type' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $id = DB::table('freelance_tags')->insertGetId([
            'name' => $request->string('name'),
            'slug' => $request->string('slug'),
            'type' => $request->string('type'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'message' => __('Tag created'),
            'data' => DB::table('freelance_tags')->find($id),
        ], Response::HTTP_CREATED);
    }

    public function update(Request $request, int $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string'],
            'slug' => ['required', 'string'],
            'type' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        DB::table('freelance_tags')->where('id', $id)->update([
            'name' => $request->string('name'),
            'slug' => $request->string('slug'),
            'type' => $request->string('type'),
            'updated_at' => now(),
        ]);

        return response()->json([
            'message' => __('Tag updated'),
            'data' => DB::table('freelance_tags')->find($id),
        ]);
    }

    public function destroy(int $id)
    {
        DB::table('freelance_tag_assignments')->where('tag_id', $id)->delete();
        DB::table('freelance_tags')->where('id', $id)->delete();

        return response()->json([
            'message' => __('Tag deleted'),
        ]);
    }

    public function saveUserTags(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tags' => ['array'],
            'tags.*' => ['string'],
            'type' => ['nullable', 'string'],
            'user_id' => ['nullable', 'integer'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $userId = $request->user()?->id ?? $request->input('user_id');
        if (!$userId) {
            return response()->json(['message' => __('User not specified')], Response::HTTP_BAD_REQUEST);
        }

        $type = $request->input('type', 'freelancer');
        $tagIds = $this->syncTags($request->input('tags', []), $type);

        DB::table('freelance_tag_assignments')->where([
            'assignable_id' => $userId,
            'assignable_type' => 'user',
        ])->delete();

        foreach ($tagIds as $tagId) {
            DB::table('freelance_tag_assignments')->insert([
                'tag_id' => $tagId,
                'assignable_id' => $userId,
                'assignable_type' => 'user',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return response()->json([
            'message' => __('Profile tags updated'),
            'data' => [
                'tags' => DB::table('freelance_tags')->whereIn('id', $tagIds)->get(),
            ],
        ]);
    }

    public function saveGigTags(Request $request, int $gigId)
    {
        $validator = Validator::make($request->all(), [
            'tags' => ['array'],
            'tags.*' => ['string'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $tagIds = $this->syncTags($request->input('tags', []), 'gig');

        DB::table('freelance_tag_assignments')->where([
            'assignable_id' => $gigId,
            'assignable_type' => 'gig',
        ])->delete();

        foreach ($tagIds as $tagId) {
            DB::table('freelance_tag_assignments')->insert([
                'tag_id' => $tagId,
                'assignable_id' => $gigId,
                'assignable_type' => 'gig',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return response()->json([
            'message' => __('Gig tags updated'),
            'data' => [
                'tags' => DB::table('freelance_tags')->whereIn('id', $tagIds)->get(),
            ],
        ]);
    }

    protected function syncTags(array $tags, string $type): array
    {
        $ids = [];
        foreach ($tags as $tag) {
            $existing = DB::table('freelance_tags')->where('slug', str($tag)->slug())->first();
            if ($existing) {
                $ids[] = $existing->id;
                continue;
            }

            $ids[] = DB::table('freelance_tags')->insertGetId([
                'name' => $tag,
                'slug' => str($tag)->slug(),
                'type' => $type,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return $ids;
    }
}
