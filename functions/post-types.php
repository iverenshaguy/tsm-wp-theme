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
 * Register Book Category Taxonomy
 */
function tsm_register_book_category_taxonomy() {
	$labels = array(
		'name'              => _x( 'Book Categories', 'taxonomy general name', 'tsm-theme' ),
		'singular_name'     => _x( 'Book Category', 'taxonomy singular name', 'tsm-theme' ),
		'search_items'      => __( 'Search Categories', 'tsm-theme' ),
		'all_items'         => __( 'All Categories', 'tsm-theme' ),
		'parent_item'       => __( 'Parent Category', 'tsm-theme' ),
		'parent_item_colon' => __( 'Parent Category:', 'tsm-theme' ),
		'edit_item'         => __( 'Edit Category', 'tsm-theme' ),
		'update_item'       => __( 'Update Category', 'tsm-theme' ),
		'add_new_item'      => __( 'Add New Category', 'tsm-theme' ),
		'new_item_name'     => __( 'New Category Name', 'tsm-theme' ),
		'menu_name'         => __( 'Categories', 'tsm-theme' ),
	);

	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'book-category' ),
		'show_in_rest'      => true,
	);

	register_taxonomy( 'book_category', array( 'book' ), $args );
}
add_action( 'init', 'tsm_register_book_category_taxonomy' );

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
	$book_amazon_url = get_post_meta( $post->ID, 'book_amazon_url', true );
	$book_selar_url = get_post_meta( $post->ID, 'book_selar_url', true );
	$book_price = get_post_meta( $post->ID, 'book_price', true );
	$book_price_original = get_post_meta( $post->ID, 'book_price_original', true );
	$book_badge = get_post_meta( $post->ID, 'book_badge', true );
	$book_featured = get_post_meta( $post->ID, 'book_featured', true );
	$book_summary = get_post_meta( $post->ID, 'book_summary', true );
	
	// Get testimonials (up to 3)
	$book_reviews = get_post_meta( $post->ID, 'book_reviews', true );
	if ( ! is_array( $book_reviews ) ) {
		$book_reviews = array();
	}
	// Ensure we have 3 review slots
	while ( count( $book_reviews ) < 3 ) {
		$book_reviews[] = array(
			'name'   => '',
			'role'   => '',
			'text'   => '',
			'rating' => 5,
		);
	}

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
			<th><label for="book_excerpt_url"><?php _e( 'Excerpt URL / Free Chapter URL', 'tsm-theme' ); ?></label></th>
			<td>
				<input type="url" id="book_excerpt_url" name="book_excerpt_url" value="<?php echo esc_url( $book_excerpt_url ); ?>" class="regular-text" />
				<p class="description"><?php _e( 'URL to download a free chapter or read an excerpt of the book.', 'tsm-theme' ); ?></p>
			</td>
		</tr>
		<tr>
			<th><label for="book_amazon_url"><?php _e( 'Amazon URL', 'tsm-theme' ); ?></label></th>
			<td>
				<input type="url" id="book_amazon_url" name="book_amazon_url" value="<?php echo esc_url( $book_amazon_url ); ?>" class="regular-text" />
				<p class="description"><?php _e( 'Amazon purchase URL for the book.', 'tsm-theme' ); ?></p>
			</td>
		</tr>
		<tr>
			<th><label for="book_selar_url"><?php _e( 'Selar URL', 'tsm-theme' ); ?></label></th>
			<td>
				<input type="url" id="book_selar_url" name="book_selar_url" value="<?php echo esc_url( $book_selar_url ); ?>" class="regular-text" />
				<p class="description"><?php _e( 'Selar purchase URL for the book.', 'tsm-theme' ); ?></p>
			</td>
		</tr>
		<tr>
			<th><label for="book_sample_download_file"><?php _e( 'Sample Download File', 'tsm-theme' ); ?></label></th>
			<td>
				<?php
				$book_sample_download_file_id = get_post_meta( $post->ID, 'book_sample_download_file_id', true );
				$book_sample_download_file_url = '';
				if ( $book_sample_download_file_id ) {
					$book_sample_download_file_url = wp_get_attachment_url( $book_sample_download_file_id );
				}
				?>
				<input type="hidden" id="book_sample_download_file_id" name="book_sample_download_file_id" value="<?php echo esc_attr( $book_sample_download_file_id ); ?>" />
				<div class="book-sample-download-file-wrapper" style="margin-bottom: 10px;">
					<?php if ( $book_sample_download_file_url ) : ?>
						<div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
							<span style="color: #46b450;">✓ File selected: <?php echo esc_html( basename( $book_sample_download_file_url ) ); ?></span>
							<button type="button" class="button remove-sample-download-file" style="color: #dc3232;">Remove</button>
						</div>
					<?php endif; ?>
					<button type="button" class="button upload-sample-download-file"><?php echo $book_sample_download_file_id ? __( 'Change File', 'tsm-theme' ) : __( 'Upload File', 'tsm-theme' ); ?></button>
				</div>
				<p class="description"><?php _e( 'Upload a PDF or other file for sample/free chapter download. If set, a "Download Free Chapter" button will appear on the book page.', 'tsm-theme' ); ?></p>
			</td>
		</tr>
		<tr>
			<th><label for="book_free_download_file"><?php _e( 'Free Download File', 'tsm-theme' ); ?></label></th>
			<td>
				<?php
				$book_free_download_file_id = get_post_meta( $post->ID, 'book_free_download_file_id', true );
				$book_free_download_file_url = '';
				if ( $book_free_download_file_id ) {
					$book_free_download_file_url = wp_get_attachment_url( $book_free_download_file_id );
				}
				?>
				<input type="hidden" id="book_free_download_file_id" name="book_free_download_file_id" value="<?php echo esc_attr( $book_free_download_file_id ); ?>" />
				<div class="book-download-file-wrapper" style="margin-bottom: 10px;">
					<?php if ( $book_free_download_file_url ) : ?>
						<div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
							<span style="color: #46b450;">✓ File selected: <?php echo esc_html( basename( $book_free_download_file_url ) ); ?></span>
							<button type="button" class="button remove-download-file" style="color: #dc3232;">Remove</button>
						</div>
					<?php endif; ?>
					<button type="button" class="button upload-download-file"><?php echo $book_free_download_file_id ? __( 'Change File', 'tsm-theme' ) : __( 'Upload File', 'tsm-theme' ); ?></button>
				</div>
				<p class="description"><?php _e( 'Upload a PDF or other file for free download. If set, a "Free Download" button will appear on the book page.', 'tsm-theme' ); ?></p>
			</td>
		</tr>
		<tr>
			<th><label for="book_price"><?php _e( 'Price', 'tsm-theme' ); ?></label></th>
			<td>
				<input type="text" id="book_price" name="book_price" value="<?php echo esc_attr( $book_price ); ?>" class="regular-text" placeholder="24.99" />
				<p class="description"><?php _e( 'Current price (e.g., 24.99).', 'tsm-theme' ); ?></p>
			</td>
		</tr>
		<tr>
			<th><label for="book_price_original"><?php _e( 'Original Price', 'tsm-theme' ); ?></label></th>
			<td>
				<input type="text" id="book_price_original" name="book_price_original" value="<?php echo esc_attr( $book_price_original ); ?>" class="regular-text" placeholder="32.00" />
				<p class="description"><?php _e( 'Original price for strikethrough display (optional).', 'tsm-theme' ); ?></p>
			</td>
		</tr>
		<tr>
			<th><label for="book_badge"><?php _e( 'Badge', 'tsm-theme' ); ?></label></th>
			<td>
				<select id="book_badge" name="book_badge">
					<option value=""><?php _e( 'None', 'tsm-theme' ); ?></option>
					<option value="Bestseller" <?php selected( $book_badge, 'Bestseller' ); ?>><?php _e( 'Bestseller', 'tsm-theme' ); ?></option>
					<option value="New" <?php selected( $book_badge, 'New' ); ?>><?php _e( 'New', 'tsm-theme' ); ?></option>
					<option value="New Arrival" <?php selected( $book_badge, 'New Arrival' ); ?>><?php _e( 'New Arrival', 'tsm-theme' ); ?></option>
				</select>
				<p class="description"><?php _e( 'Badge to display on the book cover.', 'tsm-theme' ); ?></p>
			</td>
		</tr>
		<tr>
			<th><label for="book_featured"><?php _e( 'Featured Book', 'tsm-theme' ); ?></label></th>
			<td>
				<label>
					<input type="checkbox" id="book_featured" name="book_featured" value="1" <?php checked( $book_featured, '1' ); ?> />
					<?php _e( 'Show as featured book in hero section', 'tsm-theme' ); ?>
				</label>
			</td>
		</tr>
		<tr>
			<th><label for="book_summary"><?php _e( 'Short Summary', 'tsm-theme' ); ?></label></th>
			<td>
				<textarea id="book_summary" name="book_summary" rows="4" class="large-text"><?php echo esc_textarea( $book_summary ); ?></textarea>
				<p class="description"><?php _e( 'A brief summary or description of the book (2-3 sentences). If left empty, the excerpt will be used.', 'tsm-theme' ); ?></p>
			</td>
		</tr>
		<tr>
			<th><label><?php _e( 'Testimonials', 'tsm-theme' ); ?></label></th>
			<td>
				<p class="description" style="margin-bottom: 15px;"><?php _e( 'Add up to 3 testimonials for this book.', 'tsm-theme' ); ?></p>
				<div style="display: grid; gap: 20px;">
					<?php for ( $i = 0; $i < 3; $i++ ) : 
						$review = isset( $book_reviews[ $i ] ) ? $book_reviews[ $i ] : array( 'name' => '', 'role' => '', 'text' => '', 'rating' => 5 );
					?>
						<div style="border: 1px solid #ddd; padding: 15px; border-radius: 5px; background: #f9f9f9;">
							<h4 style="margin-top: 0; margin-bottom: 10px;"><?php printf( __( 'Testimonial %d', 'tsm-theme' ), $i + 1 ); ?></h4>
							<table class="form-table" style="margin: 0;">
								<tr>
									<th style="width: 100px;"><label for="book_review_<?php echo $i; ?>_name"><?php _e( 'Name', 'tsm-theme' ); ?></label></th>
									<td>
										<input type="text" id="book_review_<?php echo $i; ?>_name" name="book_reviews[<?php echo $i; ?>][name]" value="<?php echo esc_attr( $review['name'] ); ?>" class="regular-text" />
									</td>
								</tr>
								<tr>
									<th><label for="book_review_<?php echo $i; ?>_role"><?php _e( 'Role/Title', 'tsm-theme' ); ?></label></th>
									<td>
										<input type="text" id="book_review_<?php echo $i; ?>_role" name="book_reviews[<?php echo $i; ?>][role]" value="<?php echo esc_attr( $review['role'] ); ?>" class="regular-text" placeholder="<?php _e( 'e.g., Ministry Leader', 'tsm-theme' ); ?>" />
									</td>
								</tr>
								<tr>
									<th><label for="book_review_<?php echo $i; ?>_text"><?php _e( 'Testimonial Text', 'tsm-theme' ); ?></label></th>
									<td>
										<textarea id="book_review_<?php echo $i; ?>_text" name="book_reviews[<?php echo $i; ?>][text]" rows="3" class="large-text"><?php echo esc_textarea( $review['text'] ); ?></textarea>
									</td>
								</tr>
								<tr>
									<th><label for="book_review_<?php echo $i; ?>_rating"><?php _e( 'Rating', 'tsm-theme' ); ?></label></th>
									<td>
										<select id="book_review_<?php echo $i; ?>_rating" name="book_reviews[<?php echo $i; ?>][rating]">
											<?php for ( $r = 1; $r <= 5; $r++ ) : ?>
												<option value="<?php echo $r; ?>" <?php selected( isset( $review['rating'] ) ? intval( $review['rating'] ) : 5, $r ); ?>><?php echo $r; ?> <?php _e( 'Star' . ( $r > 1 ? 's' : '' ), 'tsm-theme' ); ?></option>
											<?php endfor; ?>
										</select>
									</td>
								</tr>
							</table>
						</div>
					<?php endfor; ?>
				</div>
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

	if ( isset( $_POST['book_amazon_url'] ) ) {
		update_post_meta( $post_id, 'book_amazon_url', esc_url_raw( $_POST['book_amazon_url'] ) );
	}

	if ( isset( $_POST['book_selar_url'] ) ) {
		update_post_meta( $post_id, 'book_selar_url', esc_url_raw( $_POST['book_selar_url'] ) );
	}

	if ( isset( $_POST['book_sample_download_file_id'] ) ) {
		$file_id = absint( $_POST['book_sample_download_file_id'] );
		if ( $file_id > 0 ) {
			update_post_meta( $post_id, 'book_sample_download_file_id', $file_id );
		} else {
			delete_post_meta( $post_id, 'book_sample_download_file_id' );
		}
	}
	
	if ( isset( $_POST['book_free_download_file_id'] ) ) {
		$file_id = absint( $_POST['book_free_download_file_id'] );
		if ( $file_id > 0 ) {
			update_post_meta( $post_id, 'book_free_download_file_id', $file_id );
		} else {
			delete_post_meta( $post_id, 'book_free_download_file_id' );
		}
	}

	if ( isset( $_POST['book_price'] ) ) {
		update_post_meta( $post_id, 'book_price', sanitize_text_field( $_POST['book_price'] ) );
	}

	if ( isset( $_POST['book_price_original'] ) ) {
		update_post_meta( $post_id, 'book_price_original', sanitize_text_field( $_POST['book_price_original'] ) );
	}

	if ( isset( $_POST['book_badge'] ) ) {
		update_post_meta( $post_id, 'book_badge', sanitize_text_field( $_POST['book_badge'] ) );
	} else {
		delete_post_meta( $post_id, 'book_badge' );
	}

	if ( isset( $_POST['book_featured'] ) ) {
		update_post_meta( $post_id, 'book_featured', '1' );
	} else {
		delete_post_meta( $post_id, 'book_featured' );
	}

	if ( isset( $_POST['book_summary'] ) ) {
		update_post_meta( $post_id, 'book_summary', sanitize_textarea_field( $_POST['book_summary'] ) );
	}

	// Save testimonials
	if ( isset( $_POST['book_reviews'] ) && is_array( $_POST['book_reviews'] ) ) {
		$testimonials = array();
		foreach ( $_POST['book_reviews'] as $testimonial ) {
			// Only save if testimonial has at least a name and text
			if ( ! empty( $testimonial['name'] ) && ! empty( $testimonial['text'] ) ) {
				$testimonials[] = array(
					'name'   => sanitize_text_field( $testimonial['name'] ),
					'role'   => sanitize_text_field( $testimonial['role'] ),
					'text'   => sanitize_textarea_field( $testimonial['text'] ),
					'rating' => isset( $testimonial['rating'] ) ? absint( $testimonial['rating'] ) : 5,
				);
			}
		}
		update_post_meta( $post_id, 'book_reviews', $testimonials );
	} else {
		// Clear testimonials if none submitted
		delete_post_meta( $post_id, 'book_reviews' );
	}
}
add_action( 'save_post', 'tsm_save_book_meta_box' );

/**
 * Enqueue scripts for book meta box file upload
 */
function tsm_book_meta_box_enqueue_scripts( $hook ) {
	global $post_type;
	
	if ( ( 'post.php' === $hook || 'post-new.php' === $hook ) && 'book' === $post_type ) {
		wp_enqueue_media();
	}
}
add_action( 'admin_enqueue_scripts', 'tsm_book_meta_box_enqueue_scripts' );

/**
 * Add JavaScript for file upload in admin footer
 */
function tsm_book_meta_box_footer_scripts() {
	$screen = get_current_screen();
	if ( $screen && 'book' === $screen->post_type && ( 'post' === $screen->base || 'post-new' === $screen->base ) ) {
		?>
		<script type="text/javascript">
		jQuery(document).ready(function($) {
			// Sample download file upload
			var sampleFileFrame;
			var sampleFileInput = $("#book_sample_download_file_id");
			var sampleFileWrapper = $(".book-sample-download-file-wrapper");
			
			function initSampleUploadButton() {
				$(".upload-sample-download-file").off("click").on("click", function(e) {
					e.preventDefault();
					
					if (sampleFileFrame) {
						sampleFileFrame.open();
						return;
					}
					
					sampleFileFrame = wp.media({
						title: "Select Sample Download File",
						button: {
							text: "Use this file"
						},
						multiple: false
					});
					
					sampleFileFrame.on("select", function() {
						var attachment = sampleFileFrame.state().get("selection").first().toJSON();
						sampleFileInput.val(attachment.id);
						sampleFileWrapper.html(
							'<div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">' +
							'<span style="color: #46b450;">✓ File selected: ' + attachment.filename + '</span>' +
							'<button type="button" class="button remove-sample-download-file" style="color: #dc3232;">Remove</button>' +
							'</div>' +
							'<button type="button" class="button upload-sample-download-file">Change File</button>'
						);
						initSampleRemoveButton();
						initSampleUploadButton();
					});
					
					sampleFileFrame.open();
				});
			}
			
			function initSampleRemoveButton() {
				$(".remove-sample-download-file").off("click").on("click", function() {
					sampleFileInput.val("");
					sampleFileWrapper.html('<button type="button" class="button upload-sample-download-file">Upload File</button>');
					initSampleUploadButton();
				});
			}
			
			// Free download file upload
			var fileFrame;
			var fileInput = $("#book_free_download_file_id");
			var fileWrapper = $(".book-download-file-wrapper");
			
			function initUploadButton() {
				$(".upload-download-file").off("click").on("click", function(e) {
					e.preventDefault();
					
					if (fileFrame) {
						fileFrame.open();
						return;
					}
					
					fileFrame = wp.media({
						title: "Select Download File",
						button: {
							text: "Use this file"
						},
						multiple: false
					});
					
					fileFrame.on("select", function() {
						var attachment = fileFrame.state().get("selection").first().toJSON();
						fileInput.val(attachment.id);
						fileWrapper.html(
							'<div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">' +
							'<span style="color: #46b450;">✓ File selected: ' + attachment.filename + '</span>' +
							'<button type="button" class="button remove-download-file" style="color: #dc3232;">Remove</button>' +
							'</div>' +
							'<button type="button" class="button upload-download-file">Change File</button>'
						);
						initRemoveButton();
						initUploadButton();
					});
					
					fileFrame.open();
				});
			}
			
			function initRemoveButton() {
				$(".remove-download-file").off("click").on("click", function() {
					fileInput.val("");
					fileWrapper.html('<button type="button" class="button upload-download-file">Upload File</button>');
					initUploadButton();
				});
			}
			
			initSampleUploadButton();
			initSampleRemoveButton();
			initUploadButton();
			initRemoveButton();
		});
		</script>
		<?php
	}
}
add_action( 'admin_footer', 'tsm_book_meta_box_footer_scripts' );

/**
 * Handle book file download
 */
function tsm_handle_book_download() {
	if ( ! isset( $_GET['download_book'] ) || ! isset( $_GET['book_id'] ) ) {
		return;
	}
	
	$book_id = absint( $_GET['book_id'] );
	
	if ( ! $book_id ) {
		wp_die( 'Invalid book ID.' );
	}
	
	// Verify it's a book post type
	$book = get_post( $book_id );
	if ( ! $book || $book->post_type !== 'book' ) {
		wp_die( 'Invalid book.' );
	}
	
	// Get the file ID
	$file_id = get_post_meta( $book_id, 'book_free_download_file_id', true );
	
	if ( ! $file_id ) {
		wp_die( 'No download file available for this book.' );
	}
	
	$file_path = get_attached_file( $file_id );
	
	if ( ! $file_path || ! file_exists( $file_path ) ) {
		wp_die( 'File not found.' );
	}
	
	// Get file info
	$original_file_name = basename( $file_path );
	$file_type = wp_check_filetype( $original_file_name );
	$mime_type = $file_type['type'];
	$file_extension = $file_type['ext'];
	
	// Create a clean filename using book title
	$book_title = sanitize_file_name( $book->post_title );
	if ( empty( $book_title ) ) {
		$book_title = 'book';
	}
	$download_file_name = $book_title . '.' . $file_extension;
	
	// Set headers for download
	header( 'Content-Type: ' . $mime_type );
	header( 'Content-Disposition: attachment; filename="' . $download_file_name . '"' );
	header( 'Content-Length: ' . filesize( $file_path ) );
	header( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
	header( 'Pragma: public' );
	
	// Disable output buffering to prevent memory issues with large files
	if ( ob_get_level() ) {
		ob_end_clean();
	}
	
	// Output file
	readfile( $file_path );
	exit;
}
add_action( 'init', 'tsm_handle_book_download' );

/**
 * Handle book sample file download
 */
function tsm_handle_book_sample_download() {
	if ( ! isset( $_GET['download_sample'] ) || ! isset( $_GET['book_id'] ) ) {
		return;
	}
	
	$book_id = absint( $_GET['book_id'] );
	
	if ( ! $book_id ) {
		wp_die( 'Invalid book ID.' );
	}
	
	// Verify it's a book post type
	$book = get_post( $book_id );
	if ( ! $book || $book->post_type !== 'book' ) {
		wp_die( 'Invalid book.' );
	}
	
	// Get the file ID
	$file_id = get_post_meta( $book_id, 'book_sample_download_file_id', true );
	
	if ( ! $file_id ) {
		wp_die( 'No sample download file available for this book.' );
	}
	
	$file_path = get_attached_file( $file_id );
	
	if ( ! $file_path || ! file_exists( $file_path ) ) {
		wp_die( 'File not found.' );
	}
	
	// Get file info
	$original_file_name = basename( $file_path );
	$file_type = wp_check_filetype( $original_file_name );
	$mime_type = $file_type['type'];
	$file_extension = $file_type['ext'];
	
	// Create a clean filename using book title
	$book_title = sanitize_file_name( $book->post_title );
	if ( empty( $book_title ) ) {
		$book_title = 'book';
	}
	$download_file_name = $book_title . '-sample.' . $file_extension;
	
	// Set headers for download
	header( 'Content-Type: ' . $mime_type );
	header( 'Content-Disposition: attachment; filename="' . $download_file_name . '"' );
	header( 'Content-Length: ' . filesize( $file_path ) );
	header( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
	header( 'Pragma: public' );
	
	// Disable output buffering to prevent memory issues with large files
	if ( ob_get_level() ) {
		ob_end_clean();
	}
	
	// Output file
	readfile( $file_path );
	exit;
}
add_action( 'init', 'tsm_handle_book_sample_download' );

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
