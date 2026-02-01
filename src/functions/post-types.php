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

/**
 * Register Mission Custom Post Type
 */
function tsm_register_mission_post_type() {
	$labels = array(
		'name'               => _x( 'Missions', 'post type general name', 'tsm-theme' ),
		'singular_name'      => _x( 'Mission', 'post type singular name', 'tsm-theme' ),
		'menu_name'          => _x( 'Missions', 'admin menu', 'tsm-theme' ),
		'name_admin_bar'     => _x( 'Mission', 'add new on admin bar', 'tsm-theme' ),
		'add_new'            => _x( 'Add New', 'mission', 'tsm-theme' ),
		'add_new_item'       => __( 'Add New Mission', 'tsm-theme' ),
		'new_item'           => __( 'New Mission', 'tsm-theme' ),
		'edit_item'          => __( 'Edit Mission', 'tsm-theme' ),
		'view_item'          => __( 'View Mission', 'tsm-theme' ),
		'all_items'          => __( 'All Missions', 'tsm-theme' ),
		'search_items'       => __( 'Search Missions', 'tsm-theme' ),
		'parent_item_colon'  => __( 'Parent Missions:', 'tsm-theme' ),
		'not_found'          => __( 'No missions found.', 'tsm-theme' ),
		'not_found_in_trash' => __( 'No missions found in Trash.', 'tsm-theme' ),
	);

	$args = array(
		'labels'             => $labels,
		'description'        => __( 'Mission trips and outreach programs.', 'tsm-theme' ),
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'missions' ),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => null,
		'menu_icon'          => 'dashicons-airplane',
		'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' ),
		'show_in_rest'       => true, // Enable Gutenberg editor
	);

	register_post_type( 'mission', $args );
}
add_action( 'init', 'tsm_register_mission_post_type' );

/**
 * Set default content template for new mission posts
 */
function tsm_mission_default_content( $content, $post ) {
	// Only apply to new mission posts
	if ( 'mission' === $post->post_type && empty( $post->post_content ) ) {
		$template = "<!-- Mission Impact Content -->\n\n";
		$template .= "<h2>Mission Overview</h2>\n\n";
		$template .= "<p>Describe the mission trip, its purpose, and key activities here.</p>\n\n";
		$template .= "<h3>Key Achievements</h3>\n\n";
		$template .= "<ul>\n";
		$template .= "<li>First achievement</li>\n";
		$template .= "<li>Second achievement</li>\n";
		$template .= "<li>Third achievement</li>\n";
		$template .= "</ul>\n\n";
		$template .= "<h3>Impact on the Community</h3>\n\n";
		$template .= "<p>Describe the lasting impact this mission had on the community.</p>\n";
		
		return $template;
	}
	
	return $content;
}
add_filter( 'default_content', 'tsm_mission_default_content', 10, 2 );

/**
 * Register Gallery Custom Post Type
 */
function tsm_register_gallery_post_type() {
	$labels = array(
		'name'               => _x( 'Galleries', 'post type general name', 'tsm-theme' ),
		'singular_name'      => _x( 'Gallery', 'post type singular name', 'tsm-theme' ),
		'menu_name'          => _x( 'Galleries', 'admin menu', 'tsm-theme' ),
		'name_admin_bar'     => _x( 'Gallery', 'add new on admin bar', 'tsm-theme' ),
		'add_new'            => _x( 'Add New', 'gallery', 'tsm-theme' ),
		'add_new_item'       => __( 'Add New Gallery', 'tsm-theme' ),
		'new_item'           => __( 'New Gallery', 'tsm-theme' ),
		'edit_item'          => __( 'Edit Gallery', 'tsm-theme' ),
		'view_item'          => __( 'View Gallery', 'tsm-theme' ),
		'all_items'          => __( 'All Galleries', 'tsm-theme' ),
		'search_items'       => __( 'Search Galleries', 'tsm-theme' ),
		'parent_item_colon'  => __( 'Parent Galleries:', 'tsm-theme' ),
		'not_found'          => __( 'No galleries found.', 'tsm-theme' ),
		'not_found_in_trash' => __( 'No galleries found in Trash.', 'tsm-theme' ),
	);

	$args = array(
		'labels'             => $labels,
		'description'        => __( 'Image galleries for missions and events.', 'tsm-theme' ),
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'galleries' ),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => null,
		'menu_icon'          => 'dashicons-format-gallery',
		'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' ),
		'show_in_rest'       => true,
	);

	register_post_type( 'gallery', $args );
}
add_action( 'init', 'tsm_register_gallery_post_type' );

/**
 * Register Gallery Category Taxonomy
 */
function tsm_register_gallery_category_taxonomy() {
	$labels = array(
		'name'              => _x( 'Gallery Categories', 'taxonomy general name', 'tsm-theme' ),
		'singular_name'     => _x( 'Gallery Category', 'taxonomy singular name', 'tsm-theme' ),
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
		'rewrite'           => array( 'slug' => 'gallery-category' ),
		'show_in_rest'      => true,
	);

	register_taxonomy( 'gallery_category', array( 'gallery' ), $args );
}
add_action( 'init', 'tsm_register_gallery_category_taxonomy' );

/**
 * Add Gallery Meta Box
 */
function tsm_add_gallery_meta_boxes() {
	add_meta_box(
		'gallery_images',
		__( 'Gallery Images', 'tsm-theme' ),
		'tsm_gallery_meta_box_callback',
		'gallery',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'tsm_add_gallery_meta_boxes' );

/**
 * Gallery Meta Box Callback
 */
function tsm_gallery_meta_box_callback( $post ) {
	wp_nonce_field( 'tsm_gallery_meta_box', 'tsm_gallery_meta_box_nonce' );
	
	$gallery_images = get_post_meta( $post->ID, 'gallery_images', true );
	if ( ! is_array( $gallery_images ) ) {
		$gallery_images = array();
	}
	?>
	<table class="form-table">
		<tr>
			<th><label><?php _e( 'Gallery Images', 'tsm-theme' ); ?></label></th>
			<td>
				<p class="description" style="margin-bottom: 15px;"><?php _e( 'Add images to this gallery. These images can be used in mission pages.', 'tsm-theme' ); ?></p>
				<div id="gallery-images-container" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 15px; margin-bottom: 15px;">
					<?php
					if ( ! empty( $gallery_images ) ) :
						foreach ( $gallery_images as $index => $image_id ) :
							if ( empty( $image_id ) ) {
								continue;
							}
							$image_url = wp_get_attachment_image_url( $image_id, 'thumbnail' );
							$image_alt = get_post_meta( $image_id, '_wp_attachment_image_alt', true );
							?>
							<div class="gallery-image-item" style="position: relative; border: 1px solid #ddd; padding: 10px; border-radius: 5px;">
								<input type="hidden" name="gallery_images[]" value="<?php echo esc_attr( $image_id ); ?>" />
								<img src="<?php echo esc_url( $image_url ); ?>" style="width: 100%; height: auto; display: block;" />
								<input type="text" name="gallery_image_alt[<?php echo esc_attr( $image_id ); ?>]" value="<?php echo esc_attr( $image_alt ); ?>" placeholder="Alt text" style="width: 100%; margin-top: 5px; padding: 5px;" />
								<button type="button" class="button remove-gallery-image" style="margin-top: 5px; width: 100%; color: #dc3232;">Remove</button>
							</div>
							<?php
						endforeach;
					endif;
					?>
				</div>
				<button type="button" class="button button-primary add-gallery-image"><?php _e( 'Add Image', 'tsm-theme' ); ?></button>
			</td>
		</tr>
	</table>
	
	<script>
	jQuery(document).ready(function($) {
		var galleryFrame;
		
		$('.add-gallery-image').on('click', function(e) {
			e.preventDefault();
			
			if (galleryFrame) {
				galleryFrame.open();
				return;
			}
			
			galleryFrame = wp.media({
				title: 'Select Gallery Images',
				button: { text: 'Add to Gallery' },
				multiple: true,
				library: { type: 'image' }
			});
			
			galleryFrame.on('select', function() {
				var selection = galleryFrame.state().get('selection');
				var container = $('#gallery-images-container');
				
				selection.each(function(attachment) {
					var imageUrl = attachment.attributes.url;
					var imageId = attachment.id;
					var imageAlt = attachment.attributes.alt || '';
					
					var item = $('<div class="gallery-image-item" style="position: relative; border: 1px solid #ddd; padding: 10px; border-radius: 5px;">' +
						'<input type="hidden" name="gallery_images[]" value="' + imageId + '" />' +
						'<img src="' + imageUrl + '" style="width: 100%; height: auto; display: block;" />' +
						'<input type="text" name="gallery_image_alt[' + imageId + ']" value="' + imageAlt + '" placeholder="Alt text" style="width: 100%; margin-top: 5px; padding: 5px;" />' +
						'<button type="button" class="button remove-gallery-image" style="margin-top: 5px; width: 100%; color: #dc3232;">Remove</button>' +
						'</div>');
					
					container.append(item);
				});
			});
			
			galleryFrame.open();
		});
		
		$(document).on('click', '.remove-gallery-image', function() {
			$(this).closest('.gallery-image-item').remove();
		});
	});
	</script>
	<?php
}

/**
 * Save Gallery Meta Box
 */
function tsm_save_gallery_meta_box( $post_id ) {
	if ( ! isset( $_POST['tsm_gallery_meta_box_nonce'] ) || ! wp_verify_nonce( $_POST['tsm_gallery_meta_box_nonce'], 'tsm_gallery_meta_box' ) ) {
		return;
	}

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( isset( $_POST['post_type'] ) && 'gallery' === $_POST['post_type'] ) {
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
	}

	// Save gallery images
	if ( isset( $_POST['gallery_images'] ) && is_array( $_POST['gallery_images'] ) ) {
		$gallery_images = array_map( 'absint', $_POST['gallery_images'] );
		$gallery_images = array_filter( $gallery_images );
		update_post_meta( $post_id, 'gallery_images', $gallery_images );
		
		// Save alt text for each image
		if ( isset( $_POST['gallery_image_alt'] ) && is_array( $_POST['gallery_image_alt'] ) ) {
			foreach ( $_POST['gallery_image_alt'] as $image_id => $alt_text ) {
				update_post_meta( absint( $image_id ), '_wp_attachment_image_alt', sanitize_text_field( $alt_text ) );
			}
		}
	} else {
		delete_post_meta( $post_id, 'gallery_images' );
	}
}
add_action( 'save_post', 'tsm_save_gallery_meta_box' );

/**
 * Enqueue media uploader for gallery meta box
 */
function tsm_enqueue_gallery_media_uploader( $hook ) {
	if ( 'post.php' === $hook || 'post-new.php' === $hook ) {
		global $post_type;
		if ( 'gallery' === $post_type ) {
			wp_enqueue_media();
		}
	}
}
add_action( 'admin_enqueue_scripts', 'tsm_enqueue_gallery_media_uploader' );

/**
 * Add Mission Meta Box
 */
function tsm_add_mission_meta_boxes() {
	add_meta_box(
		'mission_details',
		__( 'Mission Details', 'tsm-theme' ),
		'tsm_mission_meta_box_callback',
		'mission',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'tsm_add_mission_meta_boxes' );

/**
 * Enqueue media uploader for mission meta box
 */
function tsm_enqueue_mission_media_uploader( $hook ) {
	if ( 'post.php' === $hook || 'post-new.php' === $hook ) {
		global $post_type;
		if ( 'mission' === $post_type ) {
			wp_enqueue_media();
		}
	}
}
add_action( 'admin_enqueue_scripts', 'tsm_enqueue_mission_media_uploader' );

/**
 * Mission Meta Box Callback
 */
function tsm_mission_meta_box_callback( $post ) {
	wp_nonce_field( 'tsm_mission_meta_box', 'tsm_mission_meta_box_nonce' );

	$mission_location = get_post_meta( $post->ID, 'mission_location', true );
	$mission_year = get_post_meta( $post->ID, 'mission_year', true );
	$mission_date = get_post_meta( $post->ID, 'mission_date', true );
	$mission_status = get_post_meta( $post->ID, 'mission_status', true );
	$mission_quote = get_post_meta( $post->ID, 'mission_quote', true );
	$mission_subtitle = get_post_meta( $post->ID, 'mission_subtitle', true );
	$mission_summary = get_post_meta( $post->ID, 'mission_summary', true );
	$mission_hero_image = get_post_meta( $post->ID, 'mission_hero_image', true );
	$mission_support_url = get_post_meta( $post->ID, 'mission_support_url', true );
	$mission_impact_title = get_post_meta( $post->ID, 'mission_impact_title', true );
	$mission_impact_description = get_post_meta( $post->ID, 'mission_impact_description', true );
	$mission_gallery_post = get_post_meta( $post->ID, 'mission_gallery_post', true );
	$mission_gallery_link = get_post_meta( $post->ID, 'mission_gallery_link', true );
	
	// Get stats (up to 4)
	$mission_stats = get_post_meta( $post->ID, 'mission_stats', true );
	if ( ! is_array( $mission_stats ) ) {
		$mission_stats = array();
	}
	while ( count( $mission_stats ) < 4 ) {
		$mission_stats[] = array( 'icon' => '', 'value' => '', 'label' => '' );
	}
	
	// Get prayer needs (up to 3)
	$mission_prayer_needs = get_post_meta( $post->ID, 'mission_prayer_needs', true );
	if ( ! is_array( $mission_prayer_needs ) ) {
		$mission_prayer_needs = array();
	}
	while ( count( $mission_prayer_needs ) < 3 ) {
		$mission_prayer_needs[] = '';
	}

	?>
	<table class="form-table">
		<tr>
			<th><label for="mission_gallery_post"><?php _e( 'Gallery Post', 'tsm-theme' ); ?></label></th>
			<td>
				<?php
				$galleries = get_posts( array(
					'post_type'      => 'gallery',
					'posts_per_page' => -1,
					'post_status'    => 'publish',
					'orderby'        => 'title',
					'order'          => 'ASC',
				) );
				?>
				<select id="mission_gallery_post" name="mission_gallery_post" class="regular-text">
					<option value=""><?php _e( '— None —', 'tsm-theme' ); ?></option>
					<?php foreach ( $galleries as $gallery ) : ?>
						<option value="<?php echo esc_attr( $gallery->ID ); ?>" <?php selected( $mission_gallery_post, $gallery->ID ); ?>>
							<?php echo esc_html( $gallery->post_title ); ?>
						</option>
					<?php endforeach; ?>
				</select>
				<p class="description"><?php _e( 'Select a gallery post to use its images for the mission gallery.', 'tsm-theme' ); ?></p>
				<?php if ( $mission_gallery_post ) : ?>
					<p style="margin-top: 10px;">
						<a href="<?php echo esc_url( get_edit_post_link( $mission_gallery_post ) ); ?>" target="_blank" class="button button-small">
							<?php _e( 'Edit Gallery', 'tsm-theme' ); ?>
						</a>
					</p>
				<?php endif; ?>
			</td>
		</tr>
		<tr>
			<th><label for="mission_gallery_link"><?php _e( 'Gallery Link', 'tsm-theme' ); ?></label></th>
			<td>
				<input type="url" id="mission_gallery_link" name="mission_gallery_link" value="<?php echo esc_url( $mission_gallery_link ); ?>" class="regular-text" placeholder="https://..." />
				<p class="description"><?php _e( 'URL for the "View all photos" button. If not set, the button will link to the selected gallery post.', 'tsm-theme' ); ?></p>
			</td>
		</tr>
		<tr>
			<th><label for="mission_location"><?php _e( 'Location', 'tsm-theme' ); ?></label></th>
			<td>
				<input type="text" id="mission_location" name="mission_location" value="<?php echo esc_attr( $mission_location ); ?>" class="regular-text" placeholder="e.g., Kenya, Guatemala, South Africa" />
				<p class="description"><?php _e( 'Location or country where the mission takes place. Only the first word will be displayed with the year.', 'tsm-theme' ); ?></p>
			</td>
		</tr>
		<tr>
			<th><label for="mission_year"><?php _e( 'Year', 'tsm-theme' ); ?></label></th>
			<td>
				<input type="text" id="mission_year" name="mission_year" value="<?php echo esc_attr( $mission_year ); ?>" class="regular-text" placeholder="e.g., 2023, 2024, 2025" />
				<p class="description"><?php _e( 'Year of the mission (e.g., "2023" or "2025").', 'tsm-theme' ); ?></p>
			</td>
		</tr>
		<tr>
			<th><label for="mission_date"><?php _e( 'Date', 'tsm-theme' ); ?></label></th>
			<td>
				<input type="text" id="mission_date" name="mission_date" value="<?php echo esc_attr( $mission_date ); ?>" class="regular-text" placeholder="e.g., July 2025, Oct 2025" />
				<p class="description"><?php _e( 'Date or timeframe for the mission (e.g., "July 2025" or "Oct 2025"). Used for detailed display.', 'tsm-theme' ); ?></p>
			</td>
		</tr>
		<tr>
			<th><label for="mission_status"><?php _e( 'Status', 'tsm-theme' ); ?></label></th>
			<td>
				<?php
				// Default to 'completed' if no status is set
				if ( empty( $mission_status ) ) {
					$mission_status = 'completed';
				}
				?>
				<select id="mission_status" name="mission_status">
					<option value="completed" <?php selected( $mission_status, 'completed' ); ?>><?php _e( 'Completed', 'tsm-theme' ); ?></option>
					<option value="upcoming" <?php selected( $mission_status, 'upcoming' ); ?>><?php _e( 'Upcoming', 'tsm-theme' ); ?></option>
					<option value="ongoing" <?php selected( $mission_status, 'ongoing' ); ?>><?php _e( 'Ongoing', 'tsm-theme' ); ?></option>
				</select>
				<p class="description"><?php _e( 'Current status of the mission.', 'tsm-theme' ); ?></p>
			</td>
		</tr>
		<tr>
			<th><label for="mission_subtitle"><?php _e( 'Subtitle', 'tsm-theme' ); ?></label></th>
			<td>
				<input type="text" id="mission_subtitle" name="mission_subtitle" value="<?php echo esc_attr( $mission_subtitle ); ?>" class="regular-text" placeholder="e.g., A Village Transformed" />
				<p class="description"><?php _e( 'Optional subtitle or tagline for the mission. If left empty, the post title will be used.', 'tsm-theme' ); ?></p>
			</td>
		</tr>
		<tr>
			<th><label for="mission_quote"><?php _e( 'Quote', 'tsm-theme' ); ?></label></th>
			<td>
				<textarea id="mission_quote" name="mission_quote" rows="3" class="large-text"><?php echo esc_textarea( $mission_quote ); ?></textarea>
				<p class="description"><?php _e( 'Optional testimonial or quote related to this mission. Will be displayed in italic style.', 'tsm-theme' ); ?></p>
			</td>
		</tr>
		<tr>
			<th><label for="mission_summary"><?php _e( 'Short Summary', 'tsm-theme' ); ?></label></th>
			<td>
				<textarea id="mission_summary" name="mission_summary" rows="4" class="large-text"><?php echo esc_textarea( $mission_summary ); ?></textarea>
				<p class="description"><?php _e( 'A brief summary or description of the mission (2-3 sentences). If left empty, the excerpt will be used.', 'tsm-theme' ); ?></p>
			</td>
		</tr>
		<tr>
			<th><label for="mission_hero_image"><?php _e( 'Hero Background Image', 'tsm-theme' ); ?></label></th>
			<td>
				<?php
				$hero_image_url = '';
				if ( $mission_hero_image ) {
					$hero_image_url = wp_get_attachment_url( $mission_hero_image );
				}
				?>
				<input type="hidden" id="mission_hero_image" name="mission_hero_image" value="<?php echo esc_attr( $mission_hero_image ); ?>" />
				<div class="mission-hero-image-wrapper" style="margin-bottom: 10px;">
					<?php if ( $hero_image_url ) : ?>
						<div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
							<img src="<?php echo esc_url( $hero_image_url ); ?>" style="max-width: 200px; height: auto;" />
							<button type="button" class="button remove-hero-image" style="color: #dc3232;">Remove</button>
						</div>
					<?php endif; ?>
					<button type="button" class="button upload-hero-image"><?php echo $mission_hero_image ? __( 'Change Image', 'tsm-theme' ) : __( 'Upload Image', 'tsm-theme' ); ?></button>
				</div>
				<p class="description"><?php _e( 'Hero background image for the mission page. If not set, featured image will be used.', 'tsm-theme' ); ?></p>
			</td>
		</tr>
		<tr>
			<th><label for="mission_impact_title"><?php _e( 'Impact Title', 'tsm-theme' ); ?></label></th>
			<td>
				<input type="text" id="mission_impact_title" name="mission_impact_title" value="<?php echo esc_attr( $mission_impact_title ); ?>" class="regular-text" placeholder="e.g., Bringing sustainable clean water..." />
				<p class="description"><?php _e( 'Title for the impact section.', 'tsm-theme' ); ?></p>
			</td>
		</tr>
		<tr>
			<th><label for="mission_impact_description"><?php _e( 'Impact Description', 'tsm-theme' ); ?></label></th>
			<td>
				<textarea id="mission_impact_description" name="mission_impact_description" rows="4" class="large-text"><?php echo esc_textarea( $mission_impact_description ); ?></textarea>
				<p class="description"><?php _e( 'Detailed description of the mission impact.', 'tsm-theme' ); ?></p>
			</td>
		</tr>
		<tr>
			<th><label for="mission_support_url"><?php _e( 'Support/Donation URL', 'tsm-theme' ); ?></label></th>
			<td>
				<input type="url" id="mission_support_url" name="mission_support_url" value="<?php echo esc_url( $mission_support_url ); ?>" class="regular-text" placeholder="https://..." />
				<p class="description"><?php _e( 'URL for the "Give to this Project" button.', 'tsm-theme' ); ?></p>
			</td>
		</tr>
		<tr>
			<th><label><?php _e( 'Mission Stats', 'tsm-theme' ); ?></label></th>
			<td>
				<p class="description" style="margin-bottom: 15px;"><?php _e( 'Add up to 4 statistics for this mission.', 'tsm-theme' ); ?></p>
				<div style="display: grid; gap: 15px;">
					<?php for ( $i = 0; $i < 4; $i++ ) : 
						$stat = isset( $mission_stats[ $i ] ) ? $mission_stats[ $i ] : array( 'icon' => '', 'value' => '', 'label' => '' );
					?>
						<div style="border: 1px solid #ddd; padding: 15px; border-radius: 5px; background: #f9f9f9;">
							<h4 style="margin-top: 0; margin-bottom: 10px;"><?php printf( __( 'Stat %d', 'tsm-theme' ), $i + 1 ); ?></h4>
							<table class="form-table" style="margin: 0;">
								<tr>
									<th style="width: 100px;"><label for="mission_stat_<?php echo $i; ?>_icon"><?php _e( 'Icon', 'tsm-theme' ); ?></label></th>
									<td>
										<input type="text" id="mission_stat_<?php echo $i; ?>_icon" name="mission_stats[<?php echo $i; ?>][icon]" value="<?php echo esc_attr( isset( $stat['icon'] ) ? $stat['icon'] : '' ); ?>" class="regular-text" placeholder="e.g., groups, water_drop, school" />
										<p class="description" style="margin: 5px 0 0 0;"><?php _e( 'Material icon name', 'tsm-theme' ); ?></p>
									</td>
								</tr>
								<tr>
									<th><label for="mission_stat_<?php echo $i; ?>_value"><?php _e( 'Value', 'tsm-theme' ); ?></label></th>
									<td>
										<input type="text" id="mission_stat_<?php echo $i; ?>_value" name="mission_stats[<?php echo $i; ?>][value]" value="<?php echo esc_attr( isset( $stat['value'] ) ? $stat['value'] : '' ); ?>" class="regular-text" placeholder="e.g., 1,200+, 3, 850" />
									</td>
								</tr>
								<tr>
									<th><label for="mission_stat_<?php echo $i; ?>_label"><?php _e( 'Label', 'tsm-theme' ); ?></label></th>
									<td>
										<input type="text" id="mission_stat_<?php echo $i; ?>_label" name="mission_stats[<?php echo $i; ?>][label]" value="<?php echo esc_attr( isset( $stat['label'] ) ? $stat['label'] : '' ); ?>" class="regular-text" placeholder="e.g., Villagers Helped, Wells Built" />
									</td>
								</tr>
							</table>
						</div>
					<?php endfor; ?>
				</div>
			</td>
		</tr>
		<tr>
			<th><label><?php _e( 'Prayer Needs', 'tsm-theme' ); ?></label></th>
			<td>
				<p class="description" style="margin-bottom: 15px;"><?php _e( 'Add up to 3 specific prayer needs for this mission.', 'tsm-theme' ); ?></p>
				<div style="display: grid; gap: 10px;">
					<?php for ( $i = 0; $i < 3; $i++ ) : 
						$prayer_need = isset( $mission_prayer_needs[ $i ] ) ? $mission_prayer_needs[ $i ] : '';
					?>
						<textarea id="mission_prayer_need_<?php echo $i; ?>" name="mission_prayer_needs[<?php echo $i; ?>]" rows="2" class="large-text" placeholder="<?php printf( __( 'Prayer need %d', 'tsm-theme' ), $i + 1 ); ?>"><?php echo esc_textarea( $prayer_need ); ?></textarea>
					<?php endfor; ?>
				</div>
			</td>
		</tr>
	</table>
	
	<script>
	jQuery(document).ready(function($) {
		// Hero image upload
		$('.upload-hero-image').on('click', function(e) {
			e.preventDefault();
			var button = $(this);
			var input = $('#mission_hero_image');
			var wrapper = button.closest('.mission-hero-image-wrapper');
			
			var frame = wp.media({
				title: 'Select Hero Image',
				button: { text: 'Use this image' },
				multiple: false
			});
			
			frame.on('select', function() {
				var attachment = frame.state().get('selection').first().toJSON();
				input.val(attachment.id);
				wrapper.find('img').remove();
				wrapper.find('.remove-hero-image').remove();
				wrapper.prepend('<div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;"><img src="' + attachment.url + '" style="max-width: 200px; height: auto;" /><button type="button" class="button remove-hero-image" style="color: #dc3232;">Remove</button></div>');
				button.text('Change Image');
			});
			
			frame.open();
		});
		
		$('.mission-hero-image-wrapper').on('click', '.remove-hero-image', function(e) {
			e.preventDefault();
			$('#mission_hero_image').val('');
			$(this).closest('div').remove();
			$('.upload-hero-image').text('Upload Image');
		});
	});
	</script>
	<?php
}

/**
 * Save Mission Meta Box
 */
function tsm_save_mission_meta_box( $post_id ) {
	// Check nonce
	if ( ! isset( $_POST['tsm_mission_meta_box_nonce'] ) || ! wp_verify_nonce( $_POST['tsm_mission_meta_box_nonce'], 'tsm_mission_meta_box' ) ) {
		return;
	}

	// Check autosave
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	// Check permissions
	if ( isset( $_POST['post_type'] ) && 'mission' === $_POST['post_type'] ) {
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
	}

	// Save meta fields
	if ( isset( $_POST['mission_location'] ) ) {
		update_post_meta( $post_id, 'mission_location', sanitize_text_field( $_POST['mission_location'] ) );
	}

	if ( isset( $_POST['mission_year'] ) ) {
		update_post_meta( $post_id, 'mission_year', sanitize_text_field( $_POST['mission_year'] ) );
	}

	if ( isset( $_POST['mission_date'] ) ) {
		update_post_meta( $post_id, 'mission_date', sanitize_text_field( $_POST['mission_date'] ) );
	}

	if ( isset( $_POST['mission_status'] ) ) {
		update_post_meta( $post_id, 'mission_status', sanitize_text_field( $_POST['mission_status'] ) );
	} else {
		// Set default to 'completed' if status is not set
		update_post_meta( $post_id, 'mission_status', 'completed' );
	}

	if ( isset( $_POST['mission_subtitle'] ) ) {
		update_post_meta( $post_id, 'mission_subtitle', sanitize_text_field( $_POST['mission_subtitle'] ) );
	}

	if ( isset( $_POST['mission_quote'] ) ) {
		update_post_meta( $post_id, 'mission_quote', sanitize_textarea_field( $_POST['mission_quote'] ) );
	}

	if ( isset( $_POST['mission_summary'] ) ) {
		update_post_meta( $post_id, 'mission_summary', sanitize_textarea_field( $_POST['mission_summary'] ) );
	}

	if ( isset( $_POST['mission_hero_image'] ) ) {
		$hero_image_id = absint( $_POST['mission_hero_image'] );
		if ( $hero_image_id > 0 ) {
			update_post_meta( $post_id, 'mission_hero_image', $hero_image_id );
		} else {
			delete_post_meta( $post_id, 'mission_hero_image' );
		}
	}

	if ( isset( $_POST['mission_impact_title'] ) ) {
		update_post_meta( $post_id, 'mission_impact_title', sanitize_text_field( $_POST['mission_impact_title'] ) );
	}

	if ( isset( $_POST['mission_impact_description'] ) ) {
		update_post_meta( $post_id, 'mission_impact_description', sanitize_textarea_field( $_POST['mission_impact_description'] ) );
	}

	if ( isset( $_POST['mission_support_url'] ) ) {
		update_post_meta( $post_id, 'mission_support_url', esc_url_raw( $_POST['mission_support_url'] ) );
	}

	if ( isset( $_POST['mission_gallery_post'] ) ) {
		$gallery_post_id = absint( $_POST['mission_gallery_post'] );
		if ( $gallery_post_id > 0 ) {
			update_post_meta( $post_id, 'mission_gallery_post', $gallery_post_id );
		} else {
			delete_post_meta( $post_id, 'mission_gallery_post' );
		}
	}

	if ( isset( $_POST['mission_gallery_link'] ) ) {
		update_post_meta( $post_id, 'mission_gallery_link', esc_url_raw( $_POST['mission_gallery_link'] ) );
	}

	// Save stats
	if ( isset( $_POST['mission_stats'] ) && is_array( $_POST['mission_stats'] ) ) {
		$stats = array();
		foreach ( $_POST['mission_stats'] as $stat ) {
			if ( ! empty( $stat['value'] ) || ! empty( $stat['label'] ) ) {
				$stats[] = array(
					'icon'  => sanitize_text_field( $stat['icon'] ),
					'value' => sanitize_text_field( $stat['value'] ),
					'label' => sanitize_text_field( $stat['label'] ),
				);
			}
		}
		update_post_meta( $post_id, 'mission_stats', $stats );
	} else {
		delete_post_meta( $post_id, 'mission_stats' );
	}

	// Save prayer needs
	if ( isset( $_POST['mission_prayer_needs'] ) && is_array( $_POST['mission_prayer_needs'] ) ) {
		$prayer_needs = array();
		foreach ( $_POST['mission_prayer_needs'] as $need ) {
			if ( ! empty( trim( $need ) ) ) {
				$prayer_needs[] = sanitize_textarea_field( $need );
			}
		}
		update_post_meta( $post_id, 'mission_prayer_needs', $prayer_needs );
	} else {
		delete_post_meta( $post_id, 'mission_prayer_needs' );
	}
}
add_action( 'save_post', 'tsm_save_mission_meta_box' );
