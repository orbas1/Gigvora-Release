<div class="gv-card friends-tab space-y-4">
    <div class="d-flex flex-wrap justify-content-between gap-3 align-items-center">
        <div>
            <p class="gv-eyebrow mb-1">{{ get_phrase('Network') }}</p>
            <h3 class="gv-heading text-xl mb-0">{{ get_phrase('Friends') }}</h3>
        </div>
    </div>

    <div class="d-flex flex-wrap gap-2" role="tablist">
        @php
            $tabs = [
                ['id' => 'home', 'label' => get_phrase('My Friends')],
                ['id' => 'profile', 'label' => get_phrase('Friend Requests')],
                ['id' => 'add_friend', 'label' => get_phrase('Find Friends')],
                ['id' => 'block_friend', 'label' => get_phrase('Block List')],
                ['id' => 'followers', 'label' => get_phrase('Followers')],
                ['id' => 'following', 'label' => get_phrase('Following')],
            ];
        @endphp
        @foreach ($tabs as $index => $tab)
            <button class="gv-pill tab-trigger @if($index === 0) active @endif"
                id="{{ $tab['id'] }}-tab"
                data-bs-toggle="tab"
                data-bs-target="#{{ $tab['id'] }}"
                type="button"
                role="tab"
                aria-controls="{{ $tab['id'] }}"
                aria-selected="{{ $index === 0 ? 'true' : 'false' }}">
                {{ $tab['label'] }}
            </button>
        @endforeach
    </div>

    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="home" role="tabpanel"
            aria-labelledby="home-tab">
            <div id="my-friends-list" class="friends-list mt-3">
                   
                  <div class="row">
                     @include('frontend.profile.friends_single_data')
                  </div>
               
            </div>
        </div>
        <!-- Tab Pane End -->
        <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
            <div class="friends-request my-3 g-2">
                <div id="my-friend-request-list" class="row">

                    @include('frontend.profile.friend_requests_single_data')
                    
                </div>
            </div>
        </div>
        <!-- Tab Pane End -->
        <!-- Tab Pane End -->
        <div class="tab-pane fade" id="add_friend" role="tabpanel" aria-labelledby="add_friend-tab">
            <div class="friends-request my-3 g-2">
                <div id="my-friend-request-list" class="row">

                    @include('frontend.profile.add_friend_data')
                    
                </div>
            </div>
        </div>
        <!-- Tab Pane End -->
        <!-- Tab Pane End -->
        <div class="tab-pane fade" id="block_friend" role="tabpanel" aria-labelledby="block_friend-tab">
            <div class="friends-request my-3 g-2">
                <div id="my-friend-request-list" class="row">

                    @include('frontend.profile.block_friend')
                    
                </div>
            </div>
        </div>
        <!-- Tab Pane End -->
        <div class="tab-pane fade" id="followers" role="tabpanel" aria-labelledby="followers-tab">
            <div class="friends-request my-3 g-2">
                <div id="my-friend-request-list" class="row">
                    @include('frontend.profile.followers')
                </div>
            </div>
        </div>
        
        <div class="tab-pane fade" id="following" role="tabpanel" aria-labelledby="following-tab">
            <div class="friends-request my-3 g-2">
                <div id="my-friend-request-list" class="row">
                    @include('frontend.profile.following')
                </div>
            </div>
        </div>
        
    </div>
    <!-- Tab Content End -->
</div>
<!-- Friends Tab End -->