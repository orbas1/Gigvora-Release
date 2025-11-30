@if(projectEnabled())
	<div class="tk-section tk-employment-section">
		<div class="tk-section_title">
			@if(!empty(pagesetting('sub-heading')))<span>{!! pagesetting('sub-heading') !!}</span></span> @endif
			@if(!empty(pagesetting('heading')))<h2>{!! pagesetting('heading') !!}</h2> @endif
			@if(!empty(pagesetting('paragraph')))
				<p>{!! pagesetting('paragraph') !!}</p>
			@endif
		</div>
		<x-projects :limit="pagesetting('no_of_projects') ?? 6" />
		<div class="tk-button-wrapper">
			<a href="{{ route('freelance.search.projects') }}" class="tk-btn-two tk-btn-task">
				@if(!empty(pagesetting('btn_text')))
					<span>{{ pagesetting('btn_text') }}</span>
				@endif 
				<i class="fa-solid fa-chevron-right"></i>
			</a>
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
			$(document).on('click', '.favorite-project', function(){
				let _this = $(this);
				var projectId = $(this).attr('data-project-id');
				var url = '{{route("favorite-project", "projectIdToReplace")}}';
				$.ajax({
					type:'POST',
					dataType:'json',
					url: url.replace('projectIdToReplace', projectId),
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