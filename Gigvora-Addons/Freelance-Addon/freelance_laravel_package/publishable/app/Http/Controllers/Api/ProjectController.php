<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Project\ProjectCollection;
use App\Http\Resources\Project\ProjectResouce;
use App\Http\Resources\ProjectDetail\ProjectDetailResouce;
use App\Services\ProjectService;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    use ApiResponser;
    private ProjectService $projectService;


    public function __construct(ProjectService $projectService) {
        $this->projectService = $projectService;
        if(!empty(request()->bearerToken())){
            $this->middleware('auth:sanctum');
        }
    }

    /**
     * Returns the list of Records
     * @param Illuminate\Http\Request
     * @return \Illuminate\Http\JsonResponse
     */

    public function index(Request $request){
        $filters = [
            'project_min_price'         => $request->project_min_price ?? '',
            'project_max_price'         => $request->project_max_price ?? '',
            'keyword'                   => $request->keyword ?? '',
            'selected_skills'           => $request->selected_skills ?? '',
            'project_type'              => $request->project_type ?? '',
            'selected_languages'        => $request->selected_languages ?? '',
            'selected_expertise_levels' => $request->selected_expertise_levels ?? '',
            'author_id'                 => $request->author_id ?? '',
            'selected_location'         => $request->selected_location ?? '',
            'order_by'                  => $request->order_by,
            'selected_category'         => $request->selected_category ?? '',
            'category_name'             => $request->category_name ?? '',
            'per_page'                  => $request->per_page ?? '',
        ];

        $projects = $this->projectService->getProjects($filters, $request->order_by ?? 'date_desc');
        return $this->success(new ProjectCollection($projects), __('project.projects_list_pagination'));
    }

    public function getProjectDetail($id){
        $project    =$this->projectService->getProject($id);
        return $this->success(new ProjectDetailResouce($project), __('project.project_detail'));
    }

    public function recentProjects(Request $request)
    {
        if(!empty(request()->bearerToken())) {
            $this->middleware('auth:sanctum');
        }
        $filters = [
            'per_page'                  => $request->per_page ?? '',
        ];
        $projects = $this->projectService->getProjects($filters);
        return $this->success(ProjectResouce::collection($projects), __('project.projects_list'));
    }
}
