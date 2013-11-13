<?php
/*
	Plugin Name: YouTube Simple Gallery
	Version: 1.0
	Description: O YouTube Simple Gallery como o pr&oacute;prio nome j&aacute; diz &eacute; um plugin que voc&ecirc; criar uma &aacute;rea de v&iacute;deos r&aacute;pidamente para o YouTube com gerenciamento via shortcode.
	Author: CHR Designer
	Author URI: http://www.chrdesigner.com
	Plugin URI: http://www.chrdesigner.com/plugin/youtube-simple-gallery.zip
	License: A slug describing license associated with the plugin (usually GPL2)
*/

require_once('custom-post-youtube-gallery.php');

add_image_size( 'chr-thumb-youtube', 320, 180, true  );

function ysg_create_page_personality_gallery() {
	$title_galeria = 'Galeria de V&#237;deo';
	$check_title=get_page_by_title($title_galeria, 'OBJECT', 'page');
	if (empty($check_title) ){
		$chr_page_gallery = array(
			'post_title' 	 => $title_galeria,
			'post_type' 	 => 'page',
			'post_name'	 	 => 'galeria-de-video',
			'comment_status' => 'closed',
			'ping_status'    => 'closed',
			'post_content'   => '[chr-youtube-gallery order="DESC" orderby="date" posts="6"]',
			'post_status'    => 'publish',
			'post_author'    => 1,
			'menu_order'     => 0
		);
		wp_insert_post( $chr_page_gallery );
	}
}
register_activation_hook( __FILE__, 'ysg_create_page_personality_gallery' );

require_once('generation-codes.php');

function ysg_script_UtubeGallery() {
	wp_enqueue_script(array('jquery', 'thickbox'));
	wp_register_style( 'style-UtubeGallery', plugins_url('/css/style-UtubeGallery.css' , __FILE__ ) );
	wp_enqueue_style( 'style-UtubeGallery' );
}  
add_action('wp_print_scripts', 'ysg_script_UtubeGallery');

require_once('content-list-gallery.php');

add_action( 'init', 'ysg_chrUtube_buttons' );

function ysg_chrUtube_buttons() {
	add_filter("mce_external_plugins", "ysg_chrUtube_add_buttons");
    add_filter('mce_buttons', 'ysg_chrUtube_register_buttons');
}	
function ysg_chrUtube_add_buttons($plugin_array) {
	$plugin_array['chrUtube'] = plugins_url( '/tinymce/chrUtube-tinymce.js' , __FILE__ );
	return $plugin_array;
}
function ysg_chrUtube_register_buttons($buttons) {
	array_push( $buttons, 'showUtube' );
	return $buttons;
}