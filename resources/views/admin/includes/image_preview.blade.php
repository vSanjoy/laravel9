<script type="text/javascript">
$(".upload-image").change(function () {
	var imagePreviewId = $(this).attr('id');
    imagePreview(this, imagePreviewId);
});
function imagePreview(input, imagePreviewId) {
    if (input.files && input.files[0]) {		
		if (input.files[0].size > {{config('global.MAX_UPLOAD_IMAGE_SIZE')}}) {
			toastr.error("@lang('custom_admin.error_max_size_image')", "@lang('custom_admin.message_error')!");
		} else {
			var fileName = input.files[0].name;
			var extension = fileName.substring(fileName.lastIndexOf('.') + 1);		
			if (extension == 'jpeg' || extension == 'jpg' || extension == 'gif' || extension == 'png' || extension == 'bmp') {
				var reader = new FileReader();
				reader.onload = function (e) {
					$('#image_holder_'+imagePreviewId).html('<img id="image_preview" class="mt-2" style="display: none;" />');
					$('#'+imagePreviewId+'_preview + div').remove();

					$('#'+imagePreviewId+'_preview').after('<div class="image-preview-holder" id="image_holder_'+imagePreviewId+'"><img src="'+e.target.result+'" class="image-preview-border" width="180" height="110"/><span class="delete-preview-image" data-cid="'+imagePreviewId+'"><i class="fa fa-trash"></i></span></div>');
				};
				reader.readAsDataURL(input.files[0]);
			} else {
				$('#'+imagePreviewId).val('');
				$('#'+imagePreviewId+'_preview + img').remove();
				toastr.error("@lang('custom_admin.error_image')", "@lang('custom_admin.message_error')!");
			}
		}
    } else {
		toastr.error("@lang('custom_admin.error_image')", "@lang('custom_admin.message_error')!");
	}
}
$(document).on('click', '.delete-preview-image', function() {
	var imageInputId = $(this).data('cid');
	$('#'+imageInputId).val('');
	$('#'+imageInputId+'_preview + div').remove();
	$('#image_holder_'+imageInputId).html('');
});
</script>