jQuery(document).ready(function($) {
	$('#upload_spinner_button').click(function() {
		tb_show('Upload a Spinner', 'media-upload.php?referer=gamwp_settings&type=image&TB_iframe=true&post_id=0', false);
		return false;
	});

	window.send_to_editor = function(html) {
		var image_url = $('img',html).attr('src');
		$('#gamwp_settings_notice_spinner').val(image_url);
		tb_remove();
		$('#notice_spinner_preview img').attr('src',image_url);

		$('#Submit').trigger('click');
	}
});