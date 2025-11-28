<li class="nav-item">
    <a class="nav-link" href="{{ route('addons.talent_ai.admin.config') }}">
        @lang('talent_ai::addons_talent_ai.menu.settings')
    </a>
</li>
<li class="nav-item">
    <a class="nav-link" href="{{ route('addons.talent_ai.admin.config', ['section' => 'plans']) }}">
        @lang('talent_ai::addons_talent_ai.menu.plans')
    </a>
</li>
<li class="nav-item">
    <a class="nav-link" href="{{ route('addons.talent_ai.admin.config', ['section' => 'headhunters']) }}">
        @lang('talent_ai::addons_talent_ai.menu.headhunter_management')
    </a>
</li>
<li class="nav-item">
    <a class="nav-link" href="{{ route('addons.talent_ai.admin.config', ['section' => 'launchpad']) }}">
        @lang('talent_ai::addons_talent_ai.menu.launchpad_moderation')
    </a>
</li>
<li class="nav-item">
    <a class="nav-link" href="{{ route('addons.talent_ai.admin.config', ['section' => 'volunteering']) }}">
        @lang('talent_ai::addons_talent_ai.menu.volunteering_moderation')
    </a>
</li>
