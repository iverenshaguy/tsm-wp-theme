<?php
/**
 * Component: Category filter pills
 *
 * @package TSM_Theme
 * 
 * @param array $args {
 *     Optional. Array of arguments.
 *     @type object $articles_category Articles category object. Default null (auto-detect).
 *     @type array  $categories        Array of category objects. Default empty array.
 *     @type string $current_slug     Current category slug. Default 'all'.
 *     @type string $posts_page_url   URL for "All" button. Default null (auto-generate).
 * }
 */

$args = wp_parse_args( isset( $args ) ? $args : array(), array(
	'articles_category' => null,
	'categories'        => array(),
	'current_slug'      => 'all',
	'posts_page_url'    => null,
) );

// Auto-detect Articles category if not provided
if ( is_null( $args['articles_category'] ) ) {
	$all_categories = get_categories( array(
		'orderby' => 'name',
		'order'   => 'ASC',
		'hide_empty' => true,
	) );
	
	foreach ( $all_categories as $cat ) {
		if ( strtolower( $cat->slug ) === 'articles' || strtolower( $cat->name ) === 'articles' ) {
			$args['articles_category'] = $cat;
			break;
		}
	}
}

// Auto-generate posts page URL if not provided
if ( is_null( $args['posts_page_url'] ) ) {
	if ( $args['articles_category'] ) {
		$args['posts_page_url'] = get_category_link( $args['articles_category']->term_id );
	} else {
		$posts_page_id = get_option( 'page_for_posts' );
		if ( $posts_page_id ) {
			$args['posts_page_url'] = get_permalink( $posts_page_id );
		} else {
			$args['posts_page_url'] = home_url( '/' );
		}
	}
}

$is_all_selected = ( $args['current_slug'] === 'all' );
?>

<!-- Category Filter Pills -->
<div class="mb-12">
	<div class="flex overflow-x-auto gap-2 no-scrollbar">
		<a href="<?php echo esc_url( $args['posts_page_url'] ); ?>" 
		   class="flex h-9 shrink-0 items-center justify-center gap-x-2 rounded-lg px-4 cursor-pointer transition-colors <?php echo $is_all_selected ? 'bg-primary' : 'bg-white dark:bg-[#162b1b] hover:bg-emerald-50 dark:hover:bg-emerald-900/30 border border-emerald-50 dark:border-emerald-900/30'; ?>">
			<p class="<?php echo $is_all_selected ? 'text-white text-sm font-semibold' : 'text-gray-700 dark:text-gray-300 text-sm font-medium'; ?>">All</p>
		</a>
		<?php if ( ! empty( $args['categories'] ) ) : ?>
			<?php foreach ( $args['categories'] as $cat ) : ?>
				<?php $is_category_selected = ( $args['current_slug'] === $cat->slug ); ?>
				<a href="<?php echo esc_url( get_category_link( $cat->term_id ) ); ?>" 
				   class="category-filter-link flex h-9 shrink-0 items-center justify-center gap-x-2 rounded-lg px-4 cursor-pointer transition-colors <?php echo $is_category_selected ? 'bg-primary border-primary' : 'bg-white dark:bg-[#162b1b] hover:bg-emerald-50 dark:hover:bg-emerald-900/30 border border-emerald-50 dark:border-emerald-900/30'; ?>">
					<p class="<?php echo $is_category_selected ? 'text-white text-sm font-semibold' : 'text-gray-700 dark:text-gray-300 text-sm font-medium'; ?>"><?php echo esc_html( $cat->name ); ?></p>
				</a>
			<?php endforeach; ?>
		<?php endif; ?>
	</div>
</div>
