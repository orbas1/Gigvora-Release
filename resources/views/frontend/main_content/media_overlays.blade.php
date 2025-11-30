@php
    $overlays = $manifest['overlays'] ?? [];
    $stickers = $manifest['stickers'] ?? [];
    $gifs = $manifest['gifs'] ?? [];
    $stickerMap = config('media_studio.stickers', []);
    $gifMap = config('media_studio.gifs', []);
    $stickerPositions = [
        ['left' => '20%', 'top' => '25%'],
        ['left' => '80%', 'top' => '20%'],
        ['left' => '50%', 'top' => '15%'],
    ];
    $gifPositions = [
        ['left' => '30%', 'top' => '80%'],
        ['left' => '70%', 'top' => '70%'],
    ];
@endphp

@foreach ($overlays as $overlay)
    @php
        $x = isset($overlay['x']) ? $overlay['x'] * 100 : 50;
        $y = isset($overlay['y']) ? $overlay['y'] * 100 : 50;
        $color = $overlay['color'] ?? '#ffffff';
        $size = $overlay['size'] ?? 18;
    @endphp
    <span class="gv-media-stage__layer" style="left: {{ $x }}%; top: {{ $y }}%; color: {{ $color }}; font-size: {{ $size }}px;">
        {{ e($overlay['value']) }}
    </span>
@endforeach

@foreach ($stickers as $index => $key)
    @php
        $asset = $stickerMap[$key]['asset'] ?? null;
        $position = $stickerPositions[$index % count($stickerPositions)];
    @endphp
    @if ($asset)
        <span class="gv-media-stage__layer" style="left: {{ $position['left'] }}; top: {{ $position['top'] }};">
            <img src="{{ asset($asset) }}" alt="{{ e($key) }}">
        </span>
    @endif
@endforeach

@foreach ($gifs as $index => $key)
    @php
        $asset = $gifMap[$key]['asset'] ?? null;
        $position = $gifPositions[$index % count($gifPositions)];
    @endphp
    @if ($asset)
        <span class="gv-media-stage__layer" style="left: {{ $position['left'] }}; top: {{ $position['top'] }};">
            <img src="{{ asset($asset) }}" alt="{{ e($key) }}">
        </span>
    @endif
@endforeach

