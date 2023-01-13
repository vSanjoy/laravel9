@php
$currentRoute 	= Route::currentRouteName();
$currentModule 	= explode('.', $currentRoute);
@endphp
<script type="text/javascript">
$(function() {
	// Process on drag
	$('.dd').nestable({
		maxDepth: 1,
		dropCallback: function(details) {    
			var order = new Array();
			$("li[data-id='"+details.destId +"']").find('ol:first').children().each(function(index,elem) {
				order[index] = $(elem).attr('data-id');
			});
				
			if (order.length === 0) {
				var order = new Array();
				$("#nestable > ol > li").each(function(index,elem) {
					order[index] = $(elem).attr('data-id');
				});
			}

			// don't post if nothing changed
			var data_id = window.location.hostname + '.nestable_admin';
			var drag_data = JSON.stringify($('.dd').nestable('serialize'));
			var storage_data = localStorage.getItem(data_id);
			if (drag_data === storage_data) {
				return false;
			} else {
				$('#loading').show();
				localStorage.setItem(data_id, drag_data);

				// post data by ajax
				$.ajax({
					url: '{{ route("admin.$currentModule[1].save-sort") }}',
					type: 'POST',
					data: {
						sourceId : details.sourceId,
						destinationId: details.destId,
						order: order
					},
					headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
					dataType: 'json',
					success: function (response) {
						$('#loading').hide();
						if (response.type == 'success') {
							toastr.success(response.message, response.title+'!');
						} else {
							toastr.error(response.message, response.title+'!');
						}						
					},
					error: function(response) {
						toastr.error(response.message, response.title+'!');
						return ;
					}
				});
			}			
		}
	}).nestable('collapseAll');    
});
</script>