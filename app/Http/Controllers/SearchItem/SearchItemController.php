<?php

namespace App\Http\Controllers\SearchItem;

use App\Http\Controllers\Controller;
use App\Models\Gig\Gig;
use App\Models\Profile;
use App\Models\Project;
use App\Models\Role;
use Illuminate\Http\Request;

class SearchItemController extends Controller
{
    public function index(Request $request)
    {
        $gigs = Gig::query()
            ->with('gigAuthor:id,first_name,last_name,slug,image')
            ->when($request->filled('search'), fn ($query) => $query->whereFullText('title', $request->input('search')))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->input('status')))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('freelance::freelancer.gigs.index', [
            'gigs' => $gigs,
            'filterUrl' => route('freelance.search.gigs', $request->all()),
        ]);
    }

    public function searchProjects(Request $request)
    {
        $projects = Project::query()
            ->with('category:id,name')
            ->when($request->filled('search'), fn ($query) => $query->whereFullText('project_title', $request->input('search')))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->input('status')))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('freelance::freelancer.projects.index', [
            'projects' => $projects,
            'filterUrl' => route('freelance.search.projects', $request->all()),
        ]);
    }

    public function searchSellers(Request $request)
    {
        $sellerRoleId = Role::where('name', config('freelance.roles.seller', 'seller'))->value('id');

        $sellers = Profile::query()
            ->when($sellerRoleId, fn ($query) => $query->where('role_id', $sellerRoleId))
            ->when($request->filled('search'), function ($query) use ($request) {
                $term = $request->input('search');
                $query->where(function ($inner) use ($term) {
                    $inner->where('first_name', 'like', "%{$term}%")
                        ->orWhere('last_name', 'like', "%{$term}%")
                        ->orWhere('tagline', 'like', "%{$term}%");
                });
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('freelance::search.freelancers', [
            'sellers' => $sellers,
            'filterUrl' => route('freelance.search.sellers', $request->all()),
        ]);
    }
}

