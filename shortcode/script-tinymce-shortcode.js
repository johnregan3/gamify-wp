jQuery(document).ready(function($) {

	tinymce.create( 'tinymce.plugins.gamwp_link_button', {

		init : function(ed, url) {

			// Register command for when button is clicked
			ed.addCommand( 'gamwp_insert_link_shortcode', function() {

				selected = tinyMCE.activeEditor.selection.getContent();

					if ( selected ){

						//If text is selected when button is clicked
						//Wrap shortcode around it.
						content = '[gamwp-link]'+selected+'[/gamwp-link]';

					} else {

						content = '[gamwp-link url="" points="" title=""]Link[/gamwp-link]';

					}

					tinymce.execCommand( 'mceInsertContent', false, content );

			}); // End insert shortcode function

			// Register buttons - trigger above command when clicked
			// Icon from http://findicons.com/pack/2354/dusseldorf
			ed.addButton('gamwp_link_button', {title : 'Insert Gamify WP Link Shortcode', cmd : 'gamwp_insert_link_shortcode', image: url + '/img/link.png' });

		},  // End init

	}); // End tinymce.create

	// Register TinyMCE plugin
	tinymce.PluginManager.add('gamwp_link_button', tinymce.plugins.gamwp_link_button);

});