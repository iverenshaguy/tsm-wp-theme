<?php
/**
 * Image Optimization: Lazy Loading and Caching
 *
 * @package TSM_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add lazy loading to all images
 */
function tsm_add_lazy_loading_to_images( $attr, $attachment, $size ) {
	// Check if this is a hero/featured image that should load immediately
	$is_hero = false;
	if ( isset( $attr['class'] ) ) {
		$is_hero = (
			strpos( $attr['class'], 'hero' ) !== false ||
			strpos( $attr['class'], 'featured' ) !== false ||
			strpos( $attr['class'], 'wp-post-image' ) !== false
		);
	}
	
	// Skip lazy loading for hero/above-the-fold images
	if ( ! $is_hero && ! isset( $attr['loading'] ) ) {
		$attr['loading'] = 'lazy';
	}
	
	// Add decoding="async" for better performance (always)
	if ( ! isset( $attr['decoding'] ) ) {
		$attr['decoding'] = 'async';
	}
	
	return $attr;
}
add_filter( 'wp_get_attachment_image_attributes', 'tsm_add_lazy_loading_to_images', 10, 3 );

/**
 * Add lazy loading to content images
 */
function tsm_add_lazy_loading_to_content_images( $content ) {
	if ( empty( $content ) ) {
		return $content;
	}
	
	// Add loading="lazy" to all img tags that don't already have it
	$content = preg_replace_callback(
		'/<img([^>]*?)>/i',
		function( $matches ) {
			$img_tag = $matches[0];
			$attributes = $matches[1];
			
			// Skip if already has loading attribute
			if ( preg_match( '/loading\s*=/i', $attributes ) ) {
				return $img_tag;
			}
			
			// Skip if it's above the fold (has specific classes or is in hero)
			if ( preg_match( '/class\s*=\s*["\'][^"\']*hero[^"\']*["\']/i', $attributes ) ||
				 preg_match( '/class\s*=\s*["\'][^"\']*above-fold[^"\']*["\']/i', $attributes ) ) {
				return $img_tag;
			}
			
			// Add loading="lazy" and decoding="async"
			$new_attributes = $attributes;
			if ( ! preg_match( '/loading\s*=/i', $new_attributes ) ) {
				$new_attributes .= ' loading="lazy"';
			}
			if ( ! preg_match( '/decoding\s*=/i', $new_attributes ) ) {
				$new_attributes .= ' decoding="async"';
			}
			
			return '<img' . $new_attributes . '>';
		},
		$content
	);
	
	return $content;
}
add_filter( 'the_content', 'tsm_add_lazy_loading_to_content_images', 99 );

/**
 * Add cache headers for images
 */
function tsm_add_image_cache_headers() {
	// Check if this is an attachment/image request
	if ( is_attachment() ) {
		$post = get_post();
		if ( $post && wp_attachment_is_image( $post->ID ) ) {
			// Set cache headers for images (1 year)
			$expires = 60 * 60 * 24 * 365; // 1 year in seconds
			
			header( 'Cache-Control: public, max-age=' . $expires );
			header( 'Expires: ' . gmdate( 'D, d M Y H:i:s', time() + $expires ) . ' GMT' );
		}
	}
}
add_action( 'template_redirect', 'tsm_add_image_cache_headers' );

/**
 * Add fetchpriority="high" to above-the-fold images
 */
function tsm_add_fetchpriority_to_hero_images( $attr, $attachment, $size ) {
	// Check if this is a hero/featured image
	global $post;
	
	if ( ! $post ) {
		return $attr;
	}
	
	// Check if image has hero-related classes or is featured image
	$is_hero = false;
	if ( isset( $attr['class'] ) ) {
		$is_hero = (
			strpos( $attr['class'], 'hero' ) !== false ||
			strpos( $attr['class'], 'featured' ) !== false ||
			strpos( $attr['class'], 'wp-post-image' ) !== false
		);
	}
	
	// Add fetchpriority="high" for hero/featured images
	if ( $is_hero && ! isset( $attr['fetchpriority'] ) ) {
		$attr['fetchpriority'] = 'high';
		// Remove lazy loading from hero images
		if ( isset( $attr['loading'] ) && $attr['loading'] === 'lazy' ) {
			unset( $attr['loading'] );
		}
	}
	
	return $attr;
}
add_filter( 'wp_get_attachment_image_attributes', 'tsm_add_fetchpriority_to_hero_images', 20, 3 );

/**
 * Add width and height attributes to prevent layout shift
 */
function tsm_add_image_dimensions( $attr, $attachment, $size ) {
	// Only add if not already present
	if ( ! isset( $attr['width'] ) && ! isset( $attr['height'] ) ) {
		$image_meta = wp_get_attachment_metadata( $attachment->ID );
		if ( $image_meta ) {
			$size_array = wp_get_attachment_image_src( $attachment->ID, $size );
			if ( $size_array && isset( $size_array[1] ) && isset( $size_array[2] ) ) {
				$attr['width'] = $size_array[1];
				$attr['height'] = $size_array[2];
			}
		}
	}
	
	return $attr;
}
add_filter( 'wp_get_attachment_image_attributes', 'tsm_add_image_dimensions', 15, 3 );
