<?php

namespace App\Services;

use App\Models\Project;
use App\Models\Proposal\Proposal;
use App\Models\Proposal\ProposalMilestone;
use App\Models\EmailTemplate;
use App\Models\User;
use App\Notifications\EmailNotification;
use App\Models\Package\PackageSubscriber;
use Illuminate\Support\Facades\Notification;
use App\Rules\OverflowRule;

class ProposalService
{
    public function submitProposal($request, $projectId)
    {
        $user               = auth()->user();        
        $profile_id         = $user->activeProfile->id;
        $proposalStatus     = setting('_proposal.proposal_default_status') ?? 'pending';

        $project = Project::select(
            'id',
            'project_title',
            'project_category',
            'author_id',
            'project_payout_type',
            'project_type',
            'project_payment_mode',
            'project_max_hours',
            'project_min_price',
            'project_max_price',
            'project_duration',
            'project_location',
            'project_country',
            'project_hiring_seller',
            'is_featured',
            'project_expert_level',
            'updated_at')
            ->with([
                'projectDuration:id,name',
                'expertiseLevel:id,name',
                'projectLocation:id,name',
                'category:id,name',
                'languages:id,name',
                'projectAuthor:id,user_id,first_name,last_name,image,description,created_at',
            ])
            ->findOrFail($projectId);

            if( $project->project_type == 'hourly' && $request->payout_type != 'hourly') {
                return response()->json(['status' => 'error', 'message' => __('proposal.hourly_error')], 400); // 400 Bad Request status code
            }

            if( $project->project_type == 'fixed' && !in_array($request->payout_type, ['fixed', 'milestone']) ) {
                return response()->json(['status' => 'error', 'message' => __('proposal.fixed_error')], 400); // 400 Bad Request status code
            }

            $params = array(
                'proposal_amount'       => $request->proposal_amount,
                'project_type'          => $project->project_type,
                'project_min_price'     => $project->project_min_price,
                'project_max_price'     => $project->project_max_price,
            );

            $commission = getAmountWithcommission($params);

            $proposalData = [
                'author_id'             => $profile_id,
                'project_id'            => $projectId,
                'proposal_amount'       => $request->proposal_amount,
                'special_comments'      => $request->special_comments,
                'payout_type'           => $request->payout_type,
                'payment_mode'          => $project->project_payment_mode,
                'commission_type'       => $commission['commission_type'] ?? 'free',
                'commission_amount'     => $commission['admin_share'] ?? 0,
                'resubmit'              => 0,
                'status'                => $proposalStatus,
            ];

            $proposal = Proposal::select('id')->create($proposalData);

            if( !empty($proposal) ){

                ProposalMilestone::where('proposal_id', $proposal->id)->delete();

                if( $request->payout_type == 'milestone' && !empty( $request->milestones) ){
                    
                    foreach( $request->milestones as  $milestone){
                        
                        $milestone['proposal_id'] = $proposal->id;
                    
                        ProposalMilestone::create($milestone);
                    }
                }
            }

            $response = [
                'status'   => 'success',
                'data'     => [
                                'title' => __('general.success_title'),
                                'message' => $proposalStatus == 'draft' ? __('proposal.proposal_draft_msg') : __('proposal.proposal_submit_msg'),
                                'type' => 'success'
                            ]
            ];
            return response()->json($response);
    }
}












        
