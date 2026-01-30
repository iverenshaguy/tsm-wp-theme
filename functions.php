<?php
/**
 * TSM Theme functions and definitions
 *
 * @package TSM_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Load theme functionality from modular files
 */
require_once get_template_directory() . '/functions/setup.php';
require_once get_template_directory() . '/functions/enqueue.php';
require_once get_template_directory() . '/functions/navigation.php';
require_once get_template_directory() . '/functions/widgets.php';
require_once get_template_directory() . '/functions/post-types.php';
require_once get_template_directory() . '/functions/customizer.php';
require_once get_template_directory() . '/functions/forms.php';
require_once get_template_directory() . '/functions/lightbox.php';
