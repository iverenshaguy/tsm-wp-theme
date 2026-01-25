<?php
/**
 * Custom post types and meta boxes
 *
 * @package TSM_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register Book Custom Post Type
 */
function tsm_register_book_post_type() {
	$labels = array(
		'name'               => _x( 'Books', 'post type general name', 'tsm-theme' ),
		'singular_name'      => _x( 'Book', 'post type singular name', 'tsm-theme' ),
		'menu_name'          => _x( 'Books', 'admin menu', 'tsm-theme' ),
		'name_admin_bar'     => _x( 'Book', 'add new on admin bar', 'tsm-theme' ),
		'add_new'            => _x( 'Add New', 'book', 'tsm-theme' ),
		'add_new_item'       => __( 'Add New Book', 'tsm-theme' ),
		'new_item'           => __( 'New Book', 'tsm-theme' ),
		'edit_item'          => __( 'Edit Book', 'tsm-theme' ),
		'view_item'          => __( 'View Book', 'tsm-theme' ),
		'all_items'          => __( 'All Books', 'tsm-theme' ),
		'search_items'       => __( 'Search Books', 'tsm-theme' ),
		'parent_item_colon'  => __( 'Parent Books:', 'tsm-theme' ),
		'not_found'          => __( 'No books found.', 'tsm-theme' ),
		'not_found_in_trash' => __( 'No books found in Trash.', 'tsm-theme' ),
	);

	$args = array(
		'labels'             => $labels,
		'description'        => __( 'Books published by the ministry.', 'tsm-theme' ),
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'books' ),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => null,
		'menu_icon'          => 'dashicons-book-alt',
		'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' ),
		'show_in_rest'       => true, // Enable Gutenberg editor
	);

	register_post_type( 'book', $args );
}
add_action( 'init', 'tsm_register_book_post_type' );

/**
 * Add Book Author Meta Box
 */
function tsm_add_book_meta_boxes() {
	add_meta_box(
		'book_details',
		__( 'Book Details', 'tsm-theme' ),
		'tsm_book_meta_box_callback',
		'book',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'tsm_add_book_meta_boxes' );

/**
 * Book Meta Box Callback
 */
function tsm_book_meta_box_callback( $post ) {
	wp_nonce_field( 'tsm_book_meta_box', 'tsm_book_meta_box_nonce' );

	$book_author = get_post_meta( $post->ID, 'book_author', true );
	$book_buy_url = get_post_meta( $post->ID, 'book_buy_url', true );
	$book_excerpt_url = get_post_meta( $post->ID, 'book_excerpt_url', true );

	?>
	<table class="form-table">
		<tr>
			<th><label for="book_author"><?php _e( 'Author', 'tsm-theme' ); ?></label></th>
			<td>
				<input type="text" id="book_author" name="book_author" value="<?php echo esc_attr( $book_author ); ?>" class="regular-text" />
				<p class="description"><?php _e( 'Author name (e.g., "Terry Shaguy" or "By Terry & Debbie Shaguy"). Leave empty to use post author.', 'tsm-theme' ); ?></p>
			</td>
		</tr>
		<tr>
			<th><label for="book_buy_url"><?php _e( 'Buy URL', 'tsm-theme' ); ?></label></th>
			<td>
				<input type="url" id="book_buy_url" name="book_buy_url" value="<?php echo esc_url( $book_buy_url ); ?>" class="regular-text" />
				<p class="description"><?php _e( 'URL where visitors can purchase the book.', 'tsm-theme' ); ?></p>
			</td>
		</tr>
		<tr>
			<th><label for="book_excerpt_url"><?php _e( 'Excerpt URL', 'tsm-theme' ); ?></label></th>
			<td>
				<input type="url" id="book_excerpt_url" name="book_excerpt_url" value="<?php echo esc_url( $book_excerpt_url ); ?>" class="regular-text" />
				<p class="description"><?php _e( 'URL to read an excerpt of the book.', 'tsm-theme' ); ?></p>
			</td>
		</tr>
	</table>
	<?php
}

/**
 * Save Book Meta Box Data
 */
function tsm_save_book_meta_box( $post_id ) {
	// Check nonce
	if ( ! isset( $_POST['tsm_book_meta_box_nonce'] ) || ! wp_verify_nonce( $_POST['tsm_book_meta_box_nonce'], 'tsm_book_meta_box' ) ) {
		return;
	}

	// Check autosave
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	// Check permissions
	if ( isset( $_POST['post_type'] ) && 'book' === $_POST['post_type'] ) {
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
	}

	// Save meta fields
	if ( isset( $_POST['book_author'] ) ) {
		update_post_meta( $post_id, 'book_author', sanitize_text_field( $_POST['book_author'] ) );
	}

	if ( isset( $_POST['book_buy_url'] ) ) {
		update_post_meta( $post_id, 'book_buy_url', esc_url_raw( $_POST['book_buy_url'] ) );
	}

	if ( isset( $_POST['book_excerpt_url'] ) ) {
		update_post_meta( $post_id, 'book_excerpt_url', esc_url_raw( $_POST['book_excerpt_url'] ) );
	}
}
add_action( 'save_post', 'tsm_save_book_meta_box' );

/**
 * Custom excerpt length
 */
function tsm_theme_excerpt_length( $length ) {
	return 55;
}
add_filter( 'excerpt_length', 'tsm_theme_excerpt_length' );

/**
 * Custom excerpt more
 */
function tsm_theme_excerpt_more( $more ) {
	return '...';
}
add_filter( 'excerpt_more', 'tsm_theme_excerpt_more' );
