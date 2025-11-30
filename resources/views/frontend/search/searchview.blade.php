<div class="page-wrap space-y-4">
    @if(isset($is_hashtag_search) && $is_hashtag_search)
        <div class="gv-card space-y-1">
            <h3 class="gv-heading text-xl mb-0">#{{ $hashtag }}</h3>
            <p class="gv-muted mb-0">{{ $hashtag_count }} {{ get_phrase('posts') }}</p>
        </div>
    @endif

    <div class="gv-card space-y-4">
        <div>
            <p class="gv-eyebrow mb-1">{{ get_phrase('Search') }}</p>
            <h3 class="gv-heading text-xl mb-0">{{ get_phrase('Search Results') }}</h3>
        </div>
        @include('frontend.search.header')
        @if(config('advertisement.enabled'))
            @php($searchAd = app(\App\Services\AdvertisementSurfaceService::class)->forSlot('search'))
            @includeWhen($searchAd, 'advertisement::components.ad_search_result', ['ad' => $searchAd])
        @endif
    </div>

    <div class="gv-card space-y-3">
        <div class="d-flex align-items-center justify-content-between">
            <h3 class="gv-heading text-lg mb-0">{{ get_phrase('Pages') }}</h3>
            @if (count($pages)>4)
                <a href="{{ url('search/page?search='.$_GET['search']) }}" class="gv-btn gv-btn-ghost gv-btn-sm">{{ get_phrase('See More') }}</a>
            @endif
        </div>
        <div class="row g-3">
            @foreach ($pages as $key => $mypage )
                <div class="col-lg-4 col-md-4 col-6">
                    <div class="gv-card h-100 space-y-2">
                        <a href="{{ route('single.page',$mypage->id) }}" class="thumbnail-133 rounded-lg w-100 d-block"
                            style="background-image: url('{{ get_page_logo($mypage->logo, 'logo') }}')"></a>
                        <div class="space-y-2">
                            <h4 class="text-base font-semibold mb-0">
                                <a href="{{ route('single.page',$mypage->id) }}" class="text-reset">
                                    {{ ellipsis($mypage->title,20) }}
                                </a>
                            </h4>
                            @php
                                $likecount = \App\Models\Page_like::where('page_id',$mypage->id)->where('user_id',auth()->user()->id)->count();
                            @endphp
                            <span class="text-sm gv-muted d-block">{{ $likecount }} {{ get_phrase('likes') }}</span>
                            @if ($likecount>0)
                                <button type="button" class="gv-btn gv-btn-ghost w-100 justify-content-center"
                                    onclick="ajaxAction('<?php echo route('page.dislike',$mypage->id); ?>')">
                                    <i class="fa fa-thumbs-up me-2"></i>{{ get_phrase('Liked') }}
                                </button>
                            @else
                                <button type="button" class="gv-btn gv-btn-primary w-100 justify-content-center"
                                    onclick="ajaxAction('<?php echo route('page.like',$mypage->id); ?>')">
                                    <i class="fa fa-thumbs-up me-2"></i>{{ get_phrase('Like') }}
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="gv-card space-y-3">
        <div class="d-flex align-items-center justify-content-between">
            <h3 class="gv-heading text-lg mb-0">{{ get_phrase('People') }}</h3>
            @if (count($peoples)>4)
                <a href="{{ url('search/people?search='.$_GET['search']) }}" class="gv-btn gv-btn-ghost gv-btn-sm">{{ get_phrase('See More') }}</a>
            @endif
        </div>
        @foreach ($peoples as $key=> $people)
            @php
                if($people->id==auth()->user()->id){
                    continue;
                }
                $user_id = $people->id;
                $friend = \App\Models\Friendships::where(function($query) use ($user_id){
                    $query->where('requester', auth()->user()->id);
                    $query->where('accepter', $user_id);
                })
                ->orWhere(function($query) use ($user_id) {
                    $query->where('accepter', auth()->user()->id);
                    $query->where('requester', $user_id);
                })
                ->count();

                $friendAccepted = \App\Models\Friendships::where(function($query) use ($user_id){
                    $query->where('requester', auth()->user()->id);
                    $query->where('accepter', $user_id);
                    $query->where('is_accepted',1);
                })
                ->orWhere(function($query) use ($user_id) {
                    $query->where('accepter', auth()->user()->id);
                    $query->where('requester', $user_id);
                    $query->where('is_accepted',1);
                })
                ->count();
            @endphp
            <div class="gv-card d-sm-flex justify-content-between w-100 gap-3">
                <div class="d-flex align-items-center gap-3">
                    <a href="{{ route('user.profile.view',$people->id) }}" class="gv-avatar gv-avatar--photo">
                        <img class="avatar-img rounded-circle user_image_show_on_modal" src="{{ get_user_image($people->photo,'optimized') }}" alt="">
                    </a>
                    <div>
                        <h6 class="mb-1">
                            <a href="{{ route('user.profile.view',$people->id) }}" class="text-reset">{{ $people->name }}</a>
                        </h6>
                        <p class="text-sm gv-muted mb-0">{{ ellipsis($people->about,'30') }}</p>
                    </div>
                </div>

                @if ($friend>0)
                    @if ($friendAccepted>0)
                        <button class="gv-btn gv-btn-ghost align-self-start">
                            <i class="fa-solid fa-user-group me-2"></i>{{ get_phrase('Friend') }}
                        </button>
                    @else
                        <button class="gv-btn gv-btn-ghost align-self-start"
                            onclick="ajaxAction('<?php echo route('user.unfriend',$people->id); ?>')"
                            data-bs-toggle="tooltip" data-bs-placement="top" title="{{ get_phrase('Cancel Friend Request') }}">
                            <i class="fa-solid fa-xmark me-2"></i>{{ get_phrase('Cancel') }}
                        </button>
                    @endif
                @else
                    <button class="gv-btn gv-btn-primary align-self-start"
                        onclick="ajaxAction('<?php echo route('user.friend',$people->id); ?>')">
                        <i class="fa-solid fa-plus me-2"></i>{{ get_phrase('Add Friend') }}
                    </button>
                @endif
            </div>
            @if ($key > 2)
                @break 
            @endif
        @endforeach
    </div>

    @if(($jobs ?? collect())->count())
        <div class="gv-card space-y-3">
            <div class="d-flex align-items-center justify-content-between">
                <h3 class="gv-heading text-lg mb-0">{{ get_phrase('Jobs') }}</h3>
                <a href="{{ route('jobs.index', ['keywords' => request('search')]) }}" class="gv-btn gv-btn-ghost gv-btn-sm">{{ get_phrase('See More') }}</a>
            </div>
            <div class="space-y-3">
                @foreach($jobs as $job)
                    @include('vendor.jobs.components.job_card', ['job' => $job, 'showActions' => true])
                @endforeach
            </div>
        </div>
    @endif

    @if (!empty($freelance_projects))
        <div class="gv-card space-y-3">
            <div class="d-flex align-items-center justify-content-between">
                <h3 class="gv-heading text-lg mb-0">{{ get_phrase('Freelance projects') }}</h3>
                <a href="{{ Route::has('freelance.projects.index') ? route('freelance.projects.index') : url('/freelance/projects') }}" class="gv-btn gv-btn-ghost gv-btn-sm">{{ get_phrase('Browse all') }}</a>
            </div>
            <div class="row g-3">
                @foreach ($freelance_projects as $project)
                    <div class="col-md-6">
                        <article class="gv-freelance-card">
                            <div class="gv-freelance-card__eyebrow">{{ $project['type'] ?? get_phrase('Fixed') }}</div>
                            <h4 class="gv-freelance-card__title">{{ $project['title'] }}</h4>
                            <p class="gv-freelance-card__summary">{{ $project['summary'] }}</p>
                            <div class="gv-freelance-card__meta">
                                <span><i class="fa-regular fa-user"></i>{{ $project['owner'] }}</span>
                                <span><i class="fa-solid fa-location-dot"></i>{{ $project['location'] }}</span>
                                @if (!empty($project['budget']))
                                    <span><i class="fa-solid fa-coins"></i>{{ $project['budget'] }}</span>
                                @endif
                            </div>
                            <div class="gv-freelance-card__actions">
                                <a href="{{ $project['link'] }}" class="gv-btn gv-btn-primary gv-btn-sm">{{ get_phrase('View brief') }}</a>
                            </div>
                        </article>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    @if (!empty($freelance_gigs))
        <div class="gv-card space-y-3">
            <div class="d-flex align-items-center justify-content-between">
                <h3 class="gv-heading text-lg mb-0">{{ get_phrase('Featured gigs') }}</h3>
                <a href="{{ Route::has('freelance.seller.gigs.list') ? route('freelance.seller.gigs.list') : url('/freelance/gigs') }}" class="gv-btn gv-btn-ghost gv-btn-sm">{{ get_phrase('See gigs') }}</a>
            </div>
            <div class="row g-3">
                @foreach ($freelance_gigs as $gig)
                    <div class="col-md-4">
                        <article class="gv-freelance-card">
                            <div class="gv-freelance-card__eyebrow">{{ get_phrase('Gig') }}</div>
                            <h4 class="gv-freelance-card__title">{{ $gig['title'] }}</h4>
                            <p class="gv-freelance-card__summary">{{ $gig['summary'] }}</p>
                            <div class="gv-freelance-card__meta">
                                <span><i class="fa-regular fa-user"></i>{{ $gig['owner'] }}</span>
                                @if (!empty($gig['price']))
                                    <span><i class="fa-solid fa-coins"></i>{{ $gig['price'] }}</span>
                                @endif
                                @if (!empty($gig['delivery']))
                                    <span><i class="fa-regular fa-clock"></i>{{ $gig['delivery'] }}</span>
                                @endif
                            </div>
                            <div class="gv-freelance-card__actions">
                                <a href="{{ $gig['link'] }}" class="gv-btn gv-btn-secondary gv-btn-sm">{{ get_phrase('Open gig') }}</a>
                            </div>
                        </article>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    @if (!empty($freelance_talent))
        <div class="gv-card space-y-3">
            <div class="d-flex align-items-center justify-content-between">
                <h3 class="gv-heading text-lg mb-0">{{ get_phrase('Freelance talent') }}</h3>
                <a href="{{ Route::has('freelance.dashboard') ? route('freelance.dashboard') : url('/freelance/dashboard') }}" class="gv-btn gv-btn-ghost gv-btn-sm">{{ get_phrase('Open workspace') }}</a>
            </div>
            <div class="space-y-3">
                @foreach ($freelance_talent as $talent)
                    <article class="gv-freelance-card gv-freelance-card--compact">
                        <div class="d-flex flex-column gap-1">
                            <h4 class="gv-freelance-card__title">{{ $talent['name'] }}</h4>
                            <p class="gv-freelance-card__summary">{{ $talent['tagline'] }}</p>
                            @if (!empty($talent['skills']))
                                <span class="gv-freelance-card__meta">
                                    <i class="fa-solid fa-sparkles me-2"></i>{{ $talent['skills'] }}
                                </span>
                            @endif
                        </div>
                        <div class="gv-freelance-card__actions">
                            <a href="{{ $talent['link'] }}" class="gv-btn gv-btn-outline">{{ get_phrase('View profile') }}</a>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    @endif

    <div class="gv-card space-y-3">
        <div class="d-flex align-items-center justify-content-between">
            <h3 class="gv-heading text-lg mb-0">{{ get_phrase('Posts') }}</h3>
            @if (count($posts)>2)
                <a href="{{ url('search/post?search='.$_GET['search']) }}" class="gv-btn gv-btn-ghost gv-btn-sm">{{ get_phrase('See More') }}</a>
            @endif
        </div>
        @include('frontend.main_content.posts',['posts'=>$posts,'search'=>'search','type'=>'user_post'])
    </div>

    <div class="gv-card space-y-3">
        <div class="d-flex align-items-center justify-content-between">
            <h3 class="gv-heading text-lg mb-0">{{ get_phrase('Marketplace') }}</h3>
            @if (count($products)>3)
                <a href="{{ url('search/product?search='.$_GET['search']) }}" class="gv-btn gv-btn-ghost gv-btn-sm">{{ get_phrase('See More') }}</a>
            @endif
        </div>
        <div class="row">
            @include('frontend.marketplace.product-single',['products'=>$products,'search'=>'search'])
        </div>
    </div>

    <div class="gv-card space-y-3">
        <div class="d-flex align-items-center justify-content-between">
            <h3 class="gv-heading text-lg mb-0">{{ get_phrase('Videos') }}</h3>
            @if (count($videos)>3)
                <a href="{{ url('search/video?search='.$_GET['search']) }}" class="gv-btn gv-btn-ghost gv-btn-sm">{{ get_phrase('See More') }}</a>
            @endif
        </div>
        @foreach ( $videos as $key => $video )
            <article class="single-entry sust_entery svideo-entry d-flex bg-white p-3 rounded-3 border border-[var(--gv-color-border)]">
                <div class="row w-100">
                    <div class="col-md-5 col-lg-5 col-sm-12">
                        <div class="entry-thumb position-relative">
                            <video class="rounded w-100 saved_video_custom_height"  controls=""
                                src="{{ asset('storage/videos/'.$video->file ) }}"></video>
                        </div>
                    </div>
                    <div class="col-md-7 col-lg-7 col-sm-12">
                        <div class="entry-text ms-4 pt-3">
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('video.detail.info',$video->id ) }}"><h3 class="h6">{{ $video->title }}</h3> </a>
                            </div>
                            <p class="save_video_p_min_height"></p>
                            <div class="d-flex my-2">
                                <div class="avatar">
                                    <a href="#!"><img class="avatar-img rounded-circle h-39 user_image_proifle_height" src="{{ get_user_image($video->getUser->photo,'optimized') }}"
                                            alt="" ></a>
                                </div>
                                <div class="avatar-info ms-2">
                                    <h4 class="ava-nave"><a href="#">{{  $video->getUser->name  }}</a></h4>
                                    <div class="activity-time">{{ date('M d ', strtotime($video->created_at)); }} at {{ date('H:i A', strtotime($video->created_at)); }}</div>
                                </div>
                            </div>
                            @php
                                $post = \App\Models\Posts::where('publisher','video_and_shorts')->where('publisher_id',$video->id)->first();
                                $user_reacts = json_decode($post->user_reacts,true);
                                $user_reacts = count($user_reacts);
                                $comment = \App\Models\Comments::where('id_of_type',$post->id)->count();
                                $view = count(json_decode($video->view,true));
                            @endphp
                            <div class="entry-footer">
                                <div class="footer-share pt-3 d-flex justify-content-between w-100">
                                    <span class="entry-react post-react"><a href="#"><img src="{{ asset('assets/frontend/images/l-react.png') }}"
                                                alt=""> {{ $user_reacts }} </a>
                                    </span>
                                    <span class="entry-react" data-bs-toggle="modal" data-bs-target="#videoChat"><a
                                            href="#">{{ $comment }} {{ get_phrase('Comments') }}</a></span>
                                    <span class="entry-react"><a href="#">{{ $view }} {{ get_phrase('Views') }}</a></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </article>
            @if ($key==2)
                @break
            @endif
        @endforeach
    </div>

    <div class="gv-card space-y-3">
        <div class="d-flex align-items-center justify-content-between">
            <h3 class="gv-heading text-lg mb-0">{{ get_phrase('Groups') }}</h3>
            @if (count($groups)>3)
                <a href="{{ url('search/group?search='.$_GET['search']) }}" class="gv-btn gv-btn-ghost gv-btn-sm">{{ get_phrase('See More') }}</a>
            @endif
        </div>
        <div class="row g-3">
            @foreach ($groups as $key => $group )
                <div class="col-md-3 col-lg-4 col-6">
                    <div class="gv-card h-100 space-y-2">
                        <div class="thumbnail-133 rounded-lg" style="background-image: url('{{ get_group_logo($group->logo,'logo') }}');"></div>
                        <div class="space-y-2">
                            <h4 class="text-base font-semibold mb-0">
                                <a href="{{ route('single.group',$group->id) }}" class="text-reset">{{ ellipsis($group->title,20) }}</a>
                            </h4>
                            @php $joined = \App\Models\Group_member::where('group_id',$group->id)->where('is_accepted','1')->count(); @endphp
                            <span class="text-sm gv-muted d-block">{{ $joined }} {{ get_phrase('Member') }} @if($joined>1) s @endif</span>
                            @php $join = \App\Models\Group_member::where('group_id',$group->id)->where('user_id',auth()->user()->id)->count(); @endphp
                            @if ($join>0)
                                <button class="gv-btn gv-btn-ghost w-100 justify-content-center"
                                    onclick="ajaxAction('<?php echo route('group.rjoin',$group->id); ?>')">
                                    {{ get_phrase('Joined') }}
                                </button>
                            @else
                                <button class="gv-btn gv-btn-primary w-100 justify-content-center"
                                    onclick="ajaxAction('<?php echo route('group.join',$group->id); ?>')">
                                    {{ get_phrase('Join') }}
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
                @if ($key==3)
                    @break
                @endif
            @endforeach
        </div>
    </div>

    <div class="gv-card space-y-3">
        <div class="d-flex align-items-center justify-content-between">
            <h3 class="gv-heading text-lg mb-0">{{ get_phrase('Events') }}</h3>
            @if (count($events)>3)
                <a href="{{ url('search/event?search='.$_GET['search']) }}" class="gv-btn gv-btn-ghost gv-btn-sm">{{ get_phrase('More Events') }}</a>
            @endif
        </div>
        <div class="row">
            @include('frontend.events.event-single',['events'=>$events,'search'=>'search'])
        </div>
    </div>
</div>

@include('frontend.main_content.scripts')
@include('frontend.initialize')
@include('frontend.common_scripts')

