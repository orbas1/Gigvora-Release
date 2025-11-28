<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class ProfileEnrichmentController
{
    public function portfolios(Request $request)
    {
        $userId = $request->user()?->id ?? $request->integer('user_id');
        if (!$userId) {
            return response()->json(['message' => __('User not specified')], Response::HTTP_BAD_REQUEST);
        }

        $portfolios = DB::table('profile_portfolios')
            ->where('user_id', $userId)
            ->orderByDesc('completed_at')
            ->orderByDesc('id')
            ->get();

        return response()->json(['data' => ['portfolios' => $portfolios]]);
    }

    public function storePortfolio(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => ['required', 'string'],
            'description' => ['nullable', 'string'],
            'link' => ['nullable', 'string'],
            'thumbnail_url' => ['nullable', 'string'],
            'featured' => ['boolean'],
            'completed_at' => ['nullable', 'date'],
            'user_id' => ['nullable', 'integer'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $userId = $request->user()?->id ?? $request->input('user_id');
        if (!$userId) {
            return response()->json(['message' => __('User not specified')], Response::HTTP_BAD_REQUEST);
        }

        $id = DB::table('profile_portfolios')->insertGetId([
            'user_id' => $userId,
            'title' => $request->string('title'),
            'description' => $request->input('description'),
            'link' => $request->input('link'),
            'thumbnail_url' => $request->input('thumbnail_url'),
            'featured' => $request->boolean('featured'),
            'completed_at' => $request->input('completed_at'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'message' => __('Portfolio added'),
            'data' => DB::table('profile_portfolios')->find($id),
        ], Response::HTTP_CREATED);
    }

    public function updatePortfolio(Request $request, int $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => ['required', 'string'],
            'description' => ['nullable', 'string'],
            'link' => ['nullable', 'string'],
            'thumbnail_url' => ['nullable', 'string'],
            'featured' => ['boolean'],
            'completed_at' => ['nullable', 'date'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        DB::table('profile_portfolios')->where('id', $id)->update([
            'title' => $request->string('title'),
            'description' => $request->input('description'),
            'link' => $request->input('link'),
            'thumbnail_url' => $request->input('thumbnail_url'),
            'featured' => $request->boolean('featured'),
            'completed_at' => $request->input('completed_at'),
            'updated_at' => now(),
        ]);

        return response()->json([
            'message' => __('Portfolio updated'),
            'data' => DB::table('profile_portfolios')->find($id),
        ]);
    }

    public function deletePortfolio(int $id)
    {
        DB::table('profile_portfolios')->where('id', $id)->delete();
        return response()->json(['message' => __('Portfolio deleted')]);
    }

    public function educations(Request $request)
    {
        $userId = $request->user()?->id ?? $request->integer('user_id');
        if (!$userId) {
            return response()->json(['message' => __('User not specified')], Response::HTTP_BAD_REQUEST);
        }

        $educations = DB::table('profile_educations')->where('user_id', $userId)->orderByDesc('start_year')->get();
        return response()->json(['data' => ['educations' => $educations]]);
    }

    public function storeEducation(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'institution' => ['required', 'string'],
            'degree' => ['nullable', 'string'],
            'field' => ['nullable', 'string'],
            'start_year' => ['nullable', 'integer'],
            'end_year' => ['nullable', 'integer'],
            'user_id' => ['nullable', 'integer'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $userId = $request->user()?->id ?? $request->input('user_id');
        if (!$userId) {
            return response()->json(['message' => __('User not specified')], Response::HTTP_BAD_REQUEST);
        }

        $id = DB::table('profile_educations')->insertGetId([
            'user_id' => $userId,
            'institution' => $request->string('institution'),
            'degree' => $request->input('degree'),
            'field' => $request->input('field'),
            'start_year' => $request->input('start_year'),
            'end_year' => $request->input('end_year'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'message' => __('Education added'),
            'data' => DB::table('profile_educations')->find($id),
        ], Response::HTTP_CREATED);
    }

    public function updateEducation(Request $request, int $id)
    {
        $validator = Validator::make($request->all(), [
            'institution' => ['required', 'string'],
            'degree' => ['nullable', 'string'],
            'field' => ['nullable', 'string'],
            'start_year' => ['nullable', 'integer'],
            'end_year' => ['nullable', 'integer'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        DB::table('profile_educations')->where('id', $id)->update([
            'institution' => $request->string('institution'),
            'degree' => $request->input('degree'),
            'field' => $request->input('field'),
            'start_year' => $request->input('start_year'),
            'end_year' => $request->input('end_year'),
            'updated_at' => now(),
        ]);

        return response()->json([
            'message' => __('Education updated'),
            'data' => DB::table('profile_educations')->find($id),
        ]);
    }

    public function deleteEducation(int $id)
    {
        DB::table('profile_educations')->where('id', $id)->delete();
        return response()->json(['message' => __('Education deleted')]);
    }

    public function certifications(Request $request)
    {
        $userId = $request->user()?->id ?? $request->integer('user_id');
        if (!$userId) {
            return response()->json(['message' => __('User not specified')], Response::HTTP_BAD_REQUEST);
        }

        $certifications = DB::table('profile_certifications')
            ->where('user_id', $userId)
            ->orderByDesc('issued_at')
            ->get();

        return response()->json(['data' => ['certifications' => $certifications]]);
    }

    public function storeCertification(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string'],
            'issuer' => ['nullable', 'string'],
            'credential_url' => ['nullable', 'string'],
            'issued_at' => ['nullable', 'date'],
            'expires_at' => ['nullable', 'date'],
            'user_id' => ['nullable', 'integer'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $userId = $request->user()?->id ?? $request->input('user_id');
        if (!$userId) {
            return response()->json(['message' => __('User not specified')], Response::HTTP_BAD_REQUEST);
        }

        $id = DB::table('profile_certifications')->insertGetId([
            'user_id' => $userId,
            'name' => $request->string('name'),
            'issuer' => $request->input('issuer'),
            'credential_url' => $request->input('credential_url'),
            'issued_at' => $request->input('issued_at'),
            'expires_at' => $request->input('expires_at'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'message' => __('Certification added'),
            'data' => DB::table('profile_certifications')->find($id),
        ], Response::HTTP_CREATED);
    }

    public function updateCertification(Request $request, int $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string'],
            'issuer' => ['nullable', 'string'],
            'credential_url' => ['nullable', 'string'],
            'issued_at' => ['nullable', 'date'],
            'expires_at' => ['nullable', 'date'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        DB::table('profile_certifications')->where('id', $id)->update([
            'name' => $request->string('name'),
            'issuer' => $request->input('issuer'),
            'credential_url' => $request->input('credential_url'),
            'issued_at' => $request->input('issued_at'),
            'expires_at' => $request->input('expires_at'),
            'updated_at' => now(),
        ]);

        return response()->json([
            'message' => __('Certification updated'),
            'data' => DB::table('profile_certifications')->find($id),
        ]);
    }

    public function deleteCertification(int $id)
    {
        DB::table('profile_certifications')->where('id', $id)->delete();
        return response()->json(['message' => __('Certification deleted')]);
    }

    public function reviews(Request $request)
    {
        $userId = $request->integer('user_id');
        if (!$userId) {
            return response()->json(['message' => __('User not specified')], Response::HTTP_BAD_REQUEST);
        }

        $reviews = DB::table('profile_reviews')->where('user_id', $userId)->orderByDesc('id')->get();
        $rating = $reviews->avg('rating');

        return response()->json(['data' => ['reviews' => $reviews, 'average_rating' => $rating]]);
    }

    public function storeReview(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => ['required', 'integer'],
            'rating' => ['required', 'numeric', 'between:0,5'],
            'comment' => ['nullable', 'string'],
            'reference' => ['nullable', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $id = DB::table('profile_reviews')->insertGetId([
            'user_id' => $request->integer('user_id'),
            'reviewer' => $request->user()?->name,
            'rating' => $request->input('rating'),
            'comment' => $request->input('comment'),
            'reference' => $request->input('reference'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'message' => __('Review added'),
            'data' => DB::table('profile_reviews')->find($id),
        ], Response::HTTP_CREATED);
    }

    public function adminOverview(int $userId)
    {
        $portfolios = DB::table('profile_portfolios')->where('user_id', $userId)->orderByDesc('id')->get();
        $educations = DB::table('profile_educations')->where('user_id', $userId)->orderByDesc('start_year')->get();
        $certifications = DB::table('profile_certifications')->where('user_id', $userId)->orderByDesc('issued_at')->get();
        $reviews = DB::table('profile_reviews')->where('user_id', $userId)->orderByDesc('id')->get();

        return response()->json([
            'data' => compact('portfolios', 'educations', 'certifications', 'reviews'),
        ]);
    }

    public function adminDeleteReview(int $id)
    {
        DB::table('profile_reviews')->where('id', $id)->delete();
        return response()->json(['message' => __('Review removed')]);
    }
}
