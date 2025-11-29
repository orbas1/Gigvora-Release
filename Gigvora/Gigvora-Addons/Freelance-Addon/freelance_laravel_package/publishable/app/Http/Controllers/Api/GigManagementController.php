<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class GigManagementController
{
    public function overview(int $gigId)
    {
        $gig = DB::table('gigs')->find($gigId);
        if (!$gig) {
            return response()->json(['message' => __('Gig not found')], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'data' => [
                'gig' => $gig,
                'timeline' => DB::table('gig_timeline_items')->where('gig_id', $gigId)->orderBy('occurred_at')->get(),
                'faqs' => DB::table('gig_faqs')->where('gig_id', $gigId)->get(),
                'addons' => DB::table('gig_addons')->where('gig_id', $gigId)->get(),
                'packages' => DB::table('gig_packages')->where('gig_id', $gigId)->get(),
                'requirements' => DB::table('gig_requirements')->where('gig_id', $gigId)->get(),
                'changes' => DB::table('gig_change_requests')->where('gig_id', $gigId)->get(),
                'reviews' => DB::table('gig_reviews')->where('gig_id', $gigId)->get(),
            ],
        ]);
    }

    public function addTimeline(Request $request, int $gigId)
    {
        $validator = Validator::make($request->all(), [
            'title' => ['required', 'string'],
            'description' => ['nullable', 'string'],
            'occurred_at' => ['required', 'date'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $id = DB::table('gig_timeline_items')->insertGetId([
            'gig_id' => $gigId,
            'title' => $request->string('title'),
            'description' => $request->string('description'),
            'occurred_at' => $request->date('occurred_at'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'message' => __('Timeline item added'),
            'data' => DB::table('gig_timeline_items')->find($id),
        ], Response::HTTP_CREATED);
    }

    public function addFaq(Request $request, int $gigId)
    {
        $validator = Validator::make($request->all(), [
            'question' => ['required', 'string'],
            'answer' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $id = DB::table('gig_faqs')->insertGetId([
            'gig_id' => $gigId,
            'question' => $request->string('question'),
            'answer' => $request->string('answer'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'message' => __('FAQ added'),
            'data' => DB::table('gig_faqs')->find($id),
        ], Response::HTTP_CREATED);
    }

    public function addAddon(Request $request, int $gigId)
    {
        $validator = Validator::make($request->all(), [
            'title' => ['required', 'string'],
            'price' => ['required', 'numeric'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $id = DB::table('gig_addons')->insertGetId([
            'gig_id' => $gigId,
            'title' => $request->string('title'),
            'price' => $request->input('price'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'message' => __('Addon added'),
            'data' => DB::table('gig_addons')->find($id),
        ], Response::HTTP_CREATED);
    }

    public function addPackage(Request $request, int $gigId)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string'],
            'price' => ['required', 'numeric'],
            'delivery_time' => ['required', 'integer'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $id = DB::table('gig_packages')->insertGetId([
            'gig_id' => $gigId,
            'name' => $request->string('name'),
            'price' => $request->input('price'),
            'delivery_time' => $request->input('delivery_time'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'message' => __('Package added'),
            'data' => DB::table('gig_packages')->find($id),
        ], Response::HTTP_CREATED);
    }

    public function requirement(Request $request, int $gigId)
    {
        $validator = Validator::make($request->all(), [
            'prompt' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $id = DB::table('gig_requirements')->insertGetId([
            'gig_id' => $gigId,
            'prompt' => $request->string('prompt'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'message' => __('Requirement added'),
            'data' => DB::table('gig_requirements')->find($id),
        ], Response::HTTP_CREATED);
    }

    public function change(Request $request, int $gigId)
    {
        $validator = Validator::make($request->all(), [
            'requester' => ['required', 'string'],
            'notes' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $id = DB::table('gig_change_requests')->insertGetId([
            'gig_id' => $gigId,
            'requester' => $request->string('requester'),
            'notes' => $request->string('notes'),
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'message' => __('Change request captured'),
            'data' => DB::table('gig_change_requests')->find($id),
        ], Response::HTTP_CREATED);
    }

    public function customGig(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => ['required', 'string'],
            'buyer' => ['required', 'string'],
            'scope' => ['nullable', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $id = DB::table('custom_gigs')->insertGetId([
            'title' => $request->string('title'),
            'buyer' => $request->string('buyer'),
            'scope' => $request->string('scope'),
            'status' => 'draft',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'message' => __('Custom gig drafted'),
            'data' => DB::table('custom_gigs')->find($id),
        ], Response::HTTP_CREATED);
    }

    public function review(Request $request, int $gigId)
    {
        $validator = Validator::make($request->all(), [
            'rating' => ['required', 'numeric', 'between:1,5'],
            'comment' => ['nullable', 'string'],
            'author' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $id = DB::table('gig_reviews')->insertGetId([
            'gig_id' => $gigId,
            'rating' => $request->input('rating'),
            'comment' => $request->string('comment'),
            'author' => $request->string('author'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'message' => __('Review recorded'),
            'data' => DB::table('gig_reviews')->find($id),
        ], Response::HTTP_CREATED);
    }
}
