<?php
	add_action('init', 'ysg_type_post_video');
	function ysg_type_post_video() { 
		$labels = array(
			'name' => __('V&iacute;deos', 'youtube-simple-gallery'),
			'singular_name' => __('V&iacute;deo', 'youtube-simple-gallery'),
			'add_new' => __('Adicionar Novo', 'youtube-simple-gallery'),
			'add_new_item' => __('Adicionar Novo V&iacute;deo', 'youtube-simple-gallery'),
			'edit_item' => __('Editar Item', 'youtube-simple-gallery'),
			'new_item' => __('Novo V&iacute;deo', 'youtube-simple-gallery'),
			'view_item' => __('Visualizar V&iacute;deo', 'youtube-simple-gallery'),
			'search_items' => __('Procurar V&iacute;deo', 'youtube-simple-gallery'),
			'not_found' =>  __('Nenhum registro encontrado', 'youtube-simple-gallery'),
			'not_found_in_trash' => __('Nenhum registro encontrado na lixeira', 'youtube-simple-gallery'),
			'parent_item_colon' => '',
			'menu_name' => __('V&iacute;deos', 'youtube-simple-gallery')
		);
		$args = array(
			'labels' => $labels,
			'public' => true,
			'public_queryable' => true,
			'show_ui' => true,          
			'query_var' => true,
			'rewrite' => true,
			'capability_type' => 'post',
			'menu_icon' => plugins_url( '/images/icon-youtube.png' , __FILE__ ),
			'has_archive' => true,
			'hierarchical' => false,
			'menu_position' => 5,
			'register_meta_box_cb' => 'ysg_video_meta_box',      
			'supports' => array('title', 'thumbnail')
		);
	register_post_type( 'youtube-gallery' , $args );
	flush_rewrite_rules();
	}
	
	// Styling for the custom post type icon
	add_action( 'admin_head', 'ysg_chr_icon_UtubeGallery' );
	function ysg_chr_icon_UtubeGallery() {
	echo'
	    <style type="text/css" media="screen">
	        #icon-edit.icon32-posts-youtube-gallery {background: url("'.plugins_url( '/images/icon-youtube-32.png' , __FILE__ ).'") no-repeat;}
	    </style>';
	}
	
	function ysg_video_meta_box(){        
		add_meta_box('meta_box_video', __('Detalhes do V&iacute;deo', 'youtube-simple-gallery'), 'ysg_meta_box_meta_video', 'youtube-gallery', 'normal', 'high');
	}

	function ysg_meta_box_meta_video(){
		global $post;

		$metaBoxUrl = get_post_meta($post->ID, 'valor_url', true); 
		$metaBoxDesc = get_post_meta($post->ID, 'valor_desc', true); 
		$metaIdVideo = ysg_youtubeEmbedFromUrl($metaBoxUrl);
		echo '
		<ul>
			<li>
				<label for="inputValorUrl" style="width:100%; display:block; font-weight: bold;">'. __('URL do v&iacute;deo:', 'youtube-simple-gallery') .'</label>
				<input style="width:100%; display:block;" type="text" name="valor_url" id="inputValorUrl" value="' . $metaBoxUrl . '" />
			</li>
			<li>
				<em style="padding: 5px 0; display: block; color: #666;"> '.
					__('Exemplos de modelos poss&iacute;veis:', 'youtube-simple-gallery').'<br />
					&bull; http://www.youtube.com/watch?v=UzifCbU_gJU<br />
					&bull; http://www.youtube.com/watch?v=UzifCbU_gJU&feature=related<br />
					&bull; http://youtu.be/UzifCbU_gJU<br />
				</em>
			</li>';
			if($metaIdVideo != null){
			?>
			<li>
				<h4><?php echo __('Imagem Original do V&iacute;deo', 'youtube-simple-gallery') ;?>:</h4>
				<?php echo '<img title="'. get_the_title().'" alt="'. get_the_title().'" src="http://img.youtube.com/vi/' . $metaIdVideo .'/mqdefault.jpg" />';	?>				
				<br />
				<em style="padding: 5px 0; display: block; color: #666;"><?php echo __('&bull; Aten&ccedil;&atilde;o: Quando tiver uma imagem destacada essa ser&aacute; alterada pela mesma.', 'youtube-simple-gallery') ;?></em>
			</li>
			<?php }; echo '
			<li>
				<label for="inputValorDesc" style="width:100%; display:block; font-weight: bold;">'. __('Descri&ccedil;&atilde;o:', 'youtube-simple-gallery') .' </label>
				<input style="width:100%; display:block;" type="text" name="valor_desc" id="inputValorDesc" value="' . $metaBoxDesc . '" />
			</li>
			<li>
				<em style="padding: 5px 0; display: block; color: #666;">'.
					__('Insir&aacute; um texto se desejar:', 'youtube-simple-gallery').'
				</em>
			</li>
		</ul>
		';}
	add_action('save_post', 'ysg_save_video_post');

	function ysg_save_video_post(){
	    global $post;        
	        update_post_meta($post->ID, 'valor_url', $_POST['valor_url']);
	        update_post_meta($post->ID, 'valor_desc', $_POST['valor_desc']);
	}