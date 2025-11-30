
@foreach($all_photos as $photo)
    <a class="gv-media-grid__item" style="background-image: url('{{ get_post_image($photo->file_name) }}')" href="{{ route('single.post', $photo->post_id) }}">
        <span class="sr-only">{{ get_phrase('View photo') }}</span>
    </a>
@endforeach
