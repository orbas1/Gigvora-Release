@foreach($navItems as $item)
    <a href="{{ $item['route'] }}" class="gv-side-link {{ $item['active'] ? 'gv-side-link--active' : '' }}">
        @if(!empty($item['icon_svg']))
            {!! $item['icon_svg'] !!}
        @else
            <img src="{{ $item['icon'] }}" alt="{{ $item['label'] }}">
        @endif
        <span>{{ $item['label'] }}</span>
    </a>
@endforeach

