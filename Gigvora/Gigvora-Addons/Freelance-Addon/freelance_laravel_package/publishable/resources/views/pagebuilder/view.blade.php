@if(gigEnabled())
	<div class="tk-work-section {{ pagesetting('theme_style') }}">
		<div class="tk-work-content">
			@if(!empty(pagesetting('sub-heading')) || !empty(pagesetting('heading')) || !empty(pagesetting('paragraph')))
				<div class="tk-work-title">
					@if(!empty(pagesetting('sub-heading')))<strong>{!! pagesetting('sub-heading') !!}</strong> @endif
					@if(!empty(pagesetting('heading')))<h4>{!! pagesetting('heading') !!}</h4> @endif
					{{-- @if(!empty(pagesetting('marketing_btn_text')))
						<a href="#" class="tk-btn-two tk-btn-task">
							<span>{{ pagesetting('marketing_btn_text') }}</span>
							<i class="icon-chevron-right"></i>
						</a>
					@endif --}}
					@if(!empty(pagesetting('paragraph')))<p>{!! pagesetting('paragraph') !!}</p>@endif
				</div>
			@endif
			@php
			$explorTaskBtn    = pagesetting('btn_text');
			@endphp

			<x-top-gigs :limit="pagesetting('theme_style') == 'tk-work-boxed' ? 8 : 6" />
		</div>
	</div>


	@pushonce(config('pagebuilder.site_script_var'))
	<script>
		document.addEventListener("DOMContentLoaded", function() {
			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				}
			});
			$(document).on('click', '.favorite-gig', function(){
				let _this = $(this);
				var gigId = $(this).attr('data-gig-id');
				var url = '{{route("favorite-gig", "gigIdToReplace")}}';
				$.ajax({
					type:'POST',
					dataType:'json',
					url: url.replace('gigIdToReplace', gigId),
					success: function() {
						_this.toggleClass("active")
					},
					error: function(xhr) {
						let err = JSON.parse(xhr.responseText);
						showAlert({
							message     : err.message,
							title       : err.title        ? err.title : '' ,
							type        : err.type,
							autoclose   : 2000,
						});
					}
				});
			});
		});
	</script>
	@endpushonce
@endif	