<?php
/**
 * Broken Links checker menu item
 */
function create_menu() {
	add_menu_page(
		'Broken Links Report',
		'Broken Links',
		'manage_options',
		plugin_dir_path(__FILE__).'bl_table.php',
		null,
		plugin_dir_url(__FILE__).'./assets/img/icon.png',
		'2'
	);
}

/**
 * Add meta key _checked = 0 when create a post
 * @param $post_id
 */
function add_checked($post_id) {
	add_post_meta($post_id, '_checked', 0);
}
add_action('wp_insert_post', 'add_checked');

/**
 * Update meta key _checked = 0 when update a post
 * @param $post_id
 * @return void
 */
function update_checked($post_id){
	update_post_meta($post_id, '_checked', 0);
}
add_action('post_updated', 'update_checked', 10, 3);

/**
 * Check if protocol http / https exist
 * @param $url
 * @return bool
 */
function check_protocol($url) {
	$protocol = parse_url($url);
	if($protocol['scheme'] == 'http' || $protocol['scheme'] == 'https') {
		return true;
	} else {
		return false;
	}
}

/**
 * Check if url has spaces
 * @param $url
 * @return bool
 */
function check_spaces($url) {
	//return str_contains($url, ' ');
	for ( $idx = 0; $idx < strlen( $url ); $idx += 1 )
		if ( ctype_space( $url[ $idx ] ) )
			return false;

	return true;
}

/**
 * Return status code
 * @param $url
 */
function status_code($url) {
	$responseHeaders = get_headers($url, 1);
	return $responseHeaders[0];
}

/**
 * Check broken links url (include href="" atribute) in posts
 */
function broken_links_checker() {
	global $wpdb;

	// retrieving unchecked posts
	$table = "{$wpdb->prefix}bl_links";
	$args = [
		'numberposts'   => -1,
		'post_type'     => 'post',
		'meta_key'      => '_checked',
		'meta_value'    => '0'
	];
	$posts = get_posts($args);

	// if exists unchecked posts
	if(!empty($posts)){
		foreach ($posts as $p ){
			$post_content = apply_filters('the_content', get_post_field('post_content', $p->ID));
			$dom = new DomDocument();
			$dom->loadHTML($post_content);
			foreach ($dom->getElementsByTagName('a') as $item) {
				// if have href="" atributte
				if(check_protocol($item->getAttribute('href'))) {
					// check spaces in url, if not spaces
					if(check_spaces($item->getAttribute('href'))) {
						// Not returning status code (generally with unprotected and insegurous website)
						if(status_code($item->getAttribute('href')) == '') {
							$data = [
								'bl_post_id' => $p->ID,
								'bl_url' => $item->getAttribute('href'),
								'bl_status' => 'Connection Error',
								'bl_origin' => $p->post_title,
							];
							$wpdb->insert($table,$data);
						}
						// code status different to 200
						elseif (!strpos(status_code($item->getAttribute('href')), "200 OK")) {
							$data = [
								'bl_post_id' => $p->ID,
								'bl_url' => $item->getAttribute('href'),
								'bl_status' => status_code($item->getAttribute('href')),
								'bl_origin' => $p->post_title,
							];
							$wpdb->insert($table,$data);
						}
					} else {
						$data = [
							'bl_post_id' => $p->ID,
							'bl_url' => $item->getAttribute('href'),
							'bl_status' => 'Malformed link',
							'bl_origin' => $p->post_title,
						];
						$wpdb->insert($table,$data);
					}
				} else {
					$data = [
						'bl_post_id' => $p->ID,
						'bl_url' => $item->getAttribute('href'),
						'bl_status' => 'Unspecified protocol',
						'bl_origin' => $p->post_title,
					];
					$wpdb->insert($table,$data);
				}
			}

			// Set posts as checked
			update_post_meta($p->ID, '_checked', 1);
		}
	}
}

