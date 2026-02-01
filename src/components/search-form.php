<?php
/**
 * Component: Articles search form
 *
 * @package TSM_Theme
 * 
 * @param array $args {
 *     Optional. Array of arguments.
 *     @type string $placeholder Placeholder text for the search input. Default 'Keywords, verses...'.
 *     @type string $input_id    ID attribute for the search input. Default empty.
 *     @type string $heading_color Heading color class. Default 'text-accent dark:text-primary/80'.
 * }
 */

$args = wp_parse_args( isset( $args ) ? $args : array(), array(
	'placeholder'   => 'Keywords, verses...',
	'input_id'      => '',
	'heading_color' => 'text-accent dark:text-primary/80',
) );

// Get Articles category URL for search form
$search_articles_category = null;
$all_cats = get_categories( array( 'hide_empty' => false ) );
foreach ( $all_cats as $cat ) {
	if ( strtolower( $cat->slug ) === 'articles' || strtolower( $cat->name ) === 'articles' ) {
		$search_articles_category = $cat;
		break;
	}
}
$search_action_url = $search_articles_category ? get_category_link( $search_articles_category->term_id ) : home_url( '/' );
?>

<div class="p-6 bg-white rounded-xl border border-gray-100 shadow-sm dark:bg-gray-900 dark:border-gray-800">
	<h3 class="mb-4 text-sm font-bold tracking-widest uppercase <?php echo esc_attr( $args['heading_color'] ); ?>">Search Articles</h3>
	<form role="search" method="get" action="<?php echo esc_url( $search_action_url ); ?>" class="relative">
		<input 
			<?php if ( ! empty( $args['input_id'] ) ) : ?>
				id="<?php echo esc_attr( $args['input_id'] ); ?>"
			<?php endif; ?>
			class="py-3 pr-4 pl-10 w-full text-sm rounded-lg border-none transition-shadow bg-background-light dark:bg-background-dark focus:ring-2 focus:ring-primary" 
			placeholder="<?php echo esc_attr( $args['placeholder'] ); ?>" 
			type="search" 
			name="s"
			value="<?php echo esc_attr( get_search_query() ? get_search_query() : ( isset( $_GET['s'] ) ? sanitize_text_field( $_GET['s'] ) : '' ) ); ?>"
		/>
		<span class="absolute left-3 top-1/2 text-lg text-gray-400 -translate-y-1/2 material-symbols-outlined">search</span>
	</form>
</div>
