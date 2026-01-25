<?php
/**
 * Theme customizer settings
 *
 * @package TSM_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Load modular customizer files
 */
require_once get_template_directory() . '/functions/customizer/front-page.php';
require_once get_template_directory() . '/functions/customizer/footer.php';
require_once get_template_directory() . '/functions/customizer/about-page.php';
require_once get_template_directory() . '/functions/customizer/ministries-page.php';
require_once get_template_directory() . '/functions/customizer/missions-page.php';
require_once get_template_directory() . '/functions/customizer/books-archive.php';
