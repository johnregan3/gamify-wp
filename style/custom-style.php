<?php

$incPath = str_replace(ABSPATH,"",getcwd());
include($incPath.'/wp-load.php');

header('Content-type: text/css');

$options = get_option( 'gamwp_settings' );

?>

#spinner-image {
	<?php if ( $options['notice_spinner'] != '' ){ ?>
		background: url(<?php echo $options['notice_spinner']; ?>);
	<?php } ?>
}

#points-notice{
	<?php echo $options['notice_css']; ?>
}