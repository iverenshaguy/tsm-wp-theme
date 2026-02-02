<?php
/**
 * Component: Newsletter/subscribe form
 *
 * @package TSM_Theme
 * 
 * @param array $args {
 *     Optional. Array of arguments.
 *     @type string $title            Form title. Default from customizer or 'Weekly Resources'.
 *     @type string $description       Form description. Default from customizer.
 *     @type string $form_id          WPForms form ID. Default from customizer.
 *     @type string $bg_color         Background color class. Default 'bg-primary dark:bg-primary'.
 *     @type string $customizer_prefix Customizer setting prefix. Default 'articles'.
 * }
 */

$args = wp_parse_args( isset( $args ) ? $args : array(), array(
	'title'            => '',
	'description'      => '',
	'form_id'          => '',
	'bg_color'         => 'bg-primary dark:bg-primary',
	'customizer_prefix' => 'articles',
) );

// Get customizer settings if not provided
$show_subscribe_form = tsm_get_theme_mod_cached( $args['customizer_prefix'] . '_show_subscribe_form', true );
if ( ! $show_subscribe_form ) {
	return;
}

$newsletter_title = ! empty( $args['title'] ) ? $args['title'] : tsm_get_theme_mod_cached( $args['customizer_prefix'] . '_newsletter_title', 'Weekly Resources' );
$newsletter_description = ! empty( $args['description'] ) ? $args['description'] : tsm_get_theme_mod_cached( $args['customizer_prefix'] . '_newsletter_description', 'Join 5,000+ others receiving weekly encouragement and articles directly in their inbox.' );
$newsletter_form_id = ! empty( $args['form_id'] ) ? $args['form_id'] : tsm_get_theme_mod_cached( $args['customizer_prefix'] . '_newsletter_form_id', '' );

// Fallback to global newsletter form ID if prefix-specific one is not set
if ( empty( $newsletter_form_id ) ) {
	$newsletter_form_id = tsm_get_theme_mod_cached( 'newsletter_form_id', '' );
}
?>

<!-- Newsletter Signup -->
<div class="overflow-hidden relative p-8 text-white rounded-xl shadow-lg <?php echo esc_attr( $args['bg_color'] ); ?>">
	<div class="relative z-10">
		<h3 class="mb-3 text-xl font-bold"><?php echo esc_html( $newsletter_title ); ?></h3>
		<p class="mb-6 text-sm leading-relaxed text-white/80">
			<?php echo esc_html( $newsletter_description ); ?>
		</p>
		<?php
		// Check if WPForms newsletter form exists
		if ( ! empty( $newsletter_form_id ) && function_exists( 'wpforms_display' ) ) {
			wpforms_display( $newsletter_form_id );
		} else {
			// Fallback simple form
			?>
			<form class="space-y-3">
				<input 
					class="px-4 py-3 mb-3 w-full text-sm text-white rounded-lg border bg-white/10 border-white/20 placeholder:text-white/50 focus:ring-primary" 
					placeholder="Email Address" 
					type="email"
				/>
				<button class="w-full bg-white dark:bg-gray-900 text-accent dark:text-white hover:shadow-xl hover:shadow-accent/50 hover:scale-[1.02] hover:border-accent/40 text-base font-bold px-6 py-3 rounded-lg border border-primary/20 dark:border-gray-700 shadow-sm transition-all flex items-center justify-center">
					Subscribe
				</button>
			</form>
			<?php
		}
		?>
	</div>
	<div class="absolute -right-8 -bottom-8 rounded-full blur-3xl size-32 bg-primary/20"></div>
</div>
