@php
$thumbImageWidth    = config('global.THUMB_IMAGE_WIDTH');   // Getting data from global file (global.php)
$thumbImageHeight	= config('global.THUMB_IMAGE_HEIGHT');  // Getting data from global file (global.php)
$imageContainer		= config('global.IMAGE_CONTAINER');  	// Getting data from global file (global.php)
@endphp

<script type="text/javascript">
var resize = $('#image-preview').croppie({
    enableExif: true,
    enableOrientation: true,    
    viewport: { // Default { width: 100, height: 100, type: 'square' } 
        width: {{ $thumbImageWidth[$modelName] }},
        height: {{ $thumbImageHeight[$modelName] }},
        type: 'square' //square
    },
    boundary: {
        width: '100%',
        height: {{ $imageContainer }}
    }
});

$('#upload_image').on('change', function () {
	if (this.files && this.files[0]) {
		if (this.files[0].size > {{ config('global.MAX_UPLOAD_IMAGE_SIZE') }}) {
			toastr.error("@lang('custom_admin.error_max_size_image')", "@lang('custom_admin.message_error')!");
		} else {
			var fileName = this.files[0].name;
			var extension = fileName.substring(fileName.lastIndexOf('.') + 1);		
			if (extension == 'jpeg' || extension == 'jpg' || extension == 'gif' || extension == 'png' || extension == 'bmp') {
				var reader = new FileReader();
				reader.onload = function (e) {
					resize.croppie('bind',{
						url: e.target.result
					}).then(function() {
						console.log('ok');
					});
				}
				reader.readAsDataURL(this.files[0]);
			} else {
				$('#upload_image').val('');
				toastr.error("@lang('custom_admin.error_image')", "@lang('custom_admin.message_error')!");
			}
		}
    } else {
		toastr.error("@lang('custom_admin.error_image')", "@lang('custom_admin.message_error')!");
	}
});

$('.crop_image').on('click', function (ev) {
  	resize.croppie('result', {
    	type: 'canvas',
    	size: 'viewport'
  	}).then(function (img) {
		html = '<img src="' + img + '" />';
		$("#preview-crop-image").html(html);

		// set to uplaod as base64
		$('#image-code-after-crop').val(img);
  	});
});
</script>