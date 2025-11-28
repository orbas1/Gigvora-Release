@php
    $style = '';
    if(!empty(pagesetting('bg_color')) && pagesetting('bg_color') != 'rgba(0,0,0,0)') {
        $style .= "background-color: ".pagesetting('bg_color').";";
    }
    if(!empty(pagesetting('border_color')) && pagesetting('border_color') != 'rgba(0,0,0,0)') {
        $style .= "border-color: ".pagesetting('border_color').";";
    }
@endphp

<div class="tk-expolreproject">
    <div class="tk-projectcard-wrapper">
        <div class="tk-cardproject" data-color="@if(!empty(pagesetting('bg_color')) && pagesetting('bg_color') != 'rgba(0,0,0,0)'){{ pagesetting('bg_color') }}@endif" style="{{ $style }}">
            <div class="tk-cardprojecttitle">
                @if(!empty(pagesetting('sub-heading')))<strong>{{ pagesetting('sub-heading') }}</strong>@endif
                @if(!empty(pagesetting('heading')))<h4>{{ pagesetting('heading') }}</h4>@endif
                @if(!empty(pagesetting('paragraph')))<p>{{ pagesetting('paragraph') }}</p>@endif
                @if(!empty(pagesetting('btn_text')))
                    <button class="tk-btn tk-btnblue">{{ pagesetting('btn_text') }}</button>
                @else
                    <a href="{{ pagesetting('btn_url') }}"><i class="fas fa-chevron-right"></i></a>
                @endif
            </div>
            <figure>
                @if(!empty(pagesetting('image'))) <img src="{{asset('storage/'.pagesetting('image')[0]['path'])}}" alt="img">@endif
            </figure>
        </div>
    </div>
</div>

@pushonce(config('pagebuilder.site_script_var'))
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.tk-cardproject').forEach(box => {
            const color = box.getAttribute('data-color');
            box.style.setProperty('--gradiant-color', color);
        });
    });
</script>
@endpushonce




