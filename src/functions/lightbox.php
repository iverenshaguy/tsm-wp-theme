<?php
/**
 * Lightbox gallery helper functions
 *
 * @package TSM_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Render a gallery lightbox
 *
 * @param array $args {
 *     Optional. Array of arguments.
 *     @type string $title        Gallery title to display in header
 *     @type string $location     Location text to display
 *     @type array  $images       Array of image data with 'full', 'thumb', 'alt' keys
 *     @type string $lightbox_id  Unique ID for this lightbox instance (default: 'gallery-lightbox')
 * }
 */
function tsm_render_lightbox_gallery( $args = array() ) {
	$defaults = array(
		'title'       => '',
		'location'    => '',
		'images'      => array(),
		'lightbox_id' => 'gallery-lightbox',
	);
	
	$args = wp_parse_args( $args, $defaults );
	
	if ( empty( $args['images'] ) ) {
		return;
	}
	
	get_template_part( 'template-parts/lightbox-gallery', null, $args );
}

/**
 * Prepare image data for lightbox from WordPress attachment IDs
 *
 * @param array $attachment_ids Array of attachment IDs
 * @return array Array of image data with 'full', 'thumb', 'alt' keys
 */
function tsm_prepare_lightbox_images( $attachment_ids ) {
	$images = array();
	
	foreach ( $attachment_ids as $attachment_id ) {
		if ( empty( $attachment_id ) ) {
			continue;
		}
		
		$images[] = array(
			'full'  => wp_get_attachment_image_url( $attachment_id, 'full' ),
			'thumb' => wp_get_attachment_image_url( $attachment_id, 'thumbnail' ),
			'alt'   => get_post_meta( $attachment_id, '_wp_attachment_image_alt', true ) ?: get_the_title( $attachment_id ),
		);
	}
	
	return $images;
}
