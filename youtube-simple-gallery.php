<?php
/*
Plugin Name: YouTube Simple Gallery
Version: 2.0.0
Description: O YouTube Simple Gallery como o pr&oacute;prio nome j&aacute; diz &eacute; um plugin que voc&ecirc; criar uma &aacute;rea de v&iacute;deos r&aacute;pidamente para o YouTube com gerenciamento via shortcode.
Author: CHR Designer
Author URI: http://www.chrdesigner.com
Plugin URI: http://wordpress.org/plugins/youtube-simple-gallery/
License: A slug describing license associated with the plugin (usually GPL2)
Text Domain: youtube-simple-gallery
Domain Path: /languages/
*/

load_plugin_textdomain( 'youtube-simple-gallery', false, plugin_basename( dirname( __FILE__ ) ) . '/languages/' );

require_once('custom-post-youtube-gallery.php');

add_image_size( 'chr-thumb-youtube', 320, 180, true  );

function ysg_create_page_personality_gallery() {
	$title_galeria = __('Galeria de V&#237;deo', 'youtube-simple-gallery');
	$check_title=get_page_by_title($title_galeria, 'OBJECT', 'page');
	if (empty($check_title) ){
		$chr_page_gallery = array(
			'post_title' 	 => $title_galeria,
			'post_type' 	 => 'page',
			'post_name'	 	 => __('galeria-de-video', 'youtube-simple-gallery'),
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

require_once('widget.php');

/*/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/// Start - Settings Page - YouTube Simple Gallery////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////*/
$ysg_options = array(
	'ysg_size_wight' => '640',
	'ysg_size_height' => '390',
	'ysg_thumb_wight' => '320',
	'ysg_thumb_height' => '180',
	'ysg_thumb_s_wight' => '160',
	'ysg_thumb_s_height' => '90',
	'ysg_autoplay' => '1'
);

if ( is_admin() ) :

function ysg_register_settings() {
	register_setting( 'ysg_plugin_options', 'ysg_options', 'ysg_validate_options' );
}
add_action( 'admin_init', 'ysg_register_settings' );

$ysg_btn_autoplay = array(
	'1' => array(
		'value' => '1',
		'label' => 'True'
	),
	'0' => array(
		'value' => '0',
		'label' => 'False'
	),
);

function ysg_plugin_options() {
	add_theme_page( 'YouTube Simple Gallery', 'YSG Settings', 'manage_options', 'ysg_settings', 'ysg_plugin_options_page' );
}
add_action( 'admin_menu', 'ysg_plugin_options' );

function ysg_plugin_options_page() {
	global $ysg_options, $ysg_btn_autoplay;

	if ( ! isset( $_REQUEST['updated'] ) )
		$_REQUEST['updated'] = false; ?>

	<div class="wrap">

		<?php screen_icon(); echo "<h2>" . __( ' YouTube Simple Gallery - Configura&ccedil;&otilde;es','youtube-simple-gallery' ) . "</h2>"; ?>
	
		<?php if ( false !== $_REQUEST['updated'] ) : ?>
		<div class="updated fade"><p><strong><?php echo __('Op&ccedil;&otilde;es Salvas','youtube-simple-gallery' );?></strong></p></div>
		<?php endif; ?>
	
		<form method="post" action="options.php">
			<?php $settings = get_option( 'ysg_options', $ysg_options ); settings_fields( 'ysg_plugin_options' ); ?>
			<h3><?php echo __('Configura&ccedil;&otilde;es padr&otilde;es do YouTube Simple Gallery','youtube-simple-gallery' );?></h3>
			<table class="form-table">
				<tr valign="top">
					<th scope="row"><label for="ysg_size_wight"><?php echo __('Tamanho do V&iacute;deo','youtube-simple-gallery' );?></label></th>
					<td>
						<input id="ysg_size_wight" name="ysg_options[ysg_size_wight]" type="text" value="<?php esc_attr_e($settings['ysg_size_wight']); ?>" style="width: 40px;" />x<input id="ysg_size_height" name="ysg_options[ysg_size_height]" type="text" value="<?php esc_attr_e($settings['ysg_size_height']); ?>" style="width: 40px;" />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="ysg_thumb_wight"><?php echo __('Tamanho do Thumbnail Maior','youtube-simple-gallery' );?></label></th>
					<td>
						<input id="ysg_thumb_wight" name="ysg_options[ysg_thumb_wight]" type="text" value="<?php esc_attr_e($settings['ysg_thumb_wight']); ?>" style="width: 40px;" />x<input id="ysg_thumb_height" name="ysg_options[ysg_thumb_height]" type="text" value="<?php esc_attr_e($settings['ysg_thumb_height']); ?>" style="width: 40px;" />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="ysg_thumb_s_wight"><?php echo __('Tamanho do Thumbnail Menor','youtube-simple-gallery' );?></label></th>
					<td>
						<input id="ysg_thumb_s_wight" name="ysg_options[ysg_thumb_s_wight]" type="text" value="<?php esc_attr_e($settings['ysg_thumb_s_wight']); ?>" style="width: 40px;" />x<input id="ysg_thumb_s_height" name="ysg_options[ysg_thumb_s_height]" type="text" value="<?php esc_attr_e($settings['ysg_thumb_s_height']); ?>" style="width: 40px;" />
					</td>
				</tr>
				<tr valign="top"><th scope="row"><?php echo __('AutoPlay','youtube-simple-gallery' );?></th>
					<td>
					<?php foreach( $ysg_btn_autoplay as $autoplay ) : ?>
						<input type="radio" id="<?php echo 'autoplay-' . $autoplay['value']; ?>" name="ysg_options[ysg_autoplay]" value="<?php esc_attr_e( $autoplay['value'] ); ?>" <?php checked( $settings['ysg_autoplay'], $autoplay['value'] ); ?> />
						<label for="<?php echo 'autoplay-' . $autoplay['value']; ?>"><?php echo $autoplay['label']; ?></label><br />
					<?php endforeach; ?>
					</td>
				</tr>
			</table>
			<p class="submit"><input type="submit" class="button-primary" value="<?php echo __('Salvar Op&ccedil;&otilde;es','youtube-simple-gallery' );?>" /></p>
		</form>
	</div>
	<?php
}
function ysg_validate_options( $input ) {
	global $ysg_options, $ysg_btn_autoplay;
	$settings = get_option( 'ysg_options', $ysg_options );
	$input['ysg_size_wight'] = wp_filter_nohtml_kses( $input['ysg_size_wight'] );
	$input['ysg_size_height'] = wp_filter_nohtml_kses( $input['ysg_size_height'] );
	$input['ysg_thumb_wight'] = wp_filter_nohtml_kses( $input['ysg_thumb_wight'] );
	$input['ysg_thumb_height'] = wp_filter_nohtml_kses( $input['ysg_thumb_height'] );
	$input['ysg_thumb_s_wight'] = wp_filter_nohtml_kses( $input['ysg_thumb_s_wight'] );
	$input['ysg_thumb_s_height'] = wp_filter_nohtml_kses( $input['ysg_thumb_s_height'] );
	$prev = $settings['ysg_autoplay'];
	if ( !array_key_exists( $input['ysg_autoplay'], $ysg_btn_autoplay ) )
		$input['ysg_autoplay'] = $prev;
	return $input;
}
endif;
/*/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/// End | Settings Page - YouTube Simple Gallery//////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////*/