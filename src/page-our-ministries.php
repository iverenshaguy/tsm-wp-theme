<?php
/**
 * The template for displaying the Our Ministries page
 *
 * @package TSM_Theme
 */

get_header();
?>

<!-- Hero Section -->
<section class="py-24 px-6 overflow-hidden">
	<div class="max-w-[1000px] mx-auto text-center">
		<div class="mb-6 inline-flex items-center gap-2 px-3 py-1 bg-primary/20 backdrop-blur-sm rounded-full border border-primary/30">
			<span class="material-symbols-outlined text-primary text-sm">visibility</span>
			<span class="text-primary text-xs font-bold tracking-widest uppercase">
				<?php
				$ministries_badge = tsm_get_theme_mod_cached( 'ministries_badge', 'The Heart of the Vision' );
				echo esc_html( $ministries_badge );
				?>
			</span>
		</div>
		<h1 class="text-4xl md:text-6xl text-primary dark:text-white font-normal italic leading-[1.15] mb-12 serif-text">
			<?php
			$ministries_vision = tsm_get_theme_mod_cached(
				'ministries_vision',
				'"Breaking poverty through <span class="text-accent">wise enterprise</span> and <span class="text-accent font-bold not-italic">community spirit</span>."'
			);
			echo wp_kses_post( $ministries_vision );
			?>
		</h1>
		<div class="flex flex-col items-center gap-4">
			<div class="w-px h-16 bg-gradient-to-b from-transparent to-accent/30"></div>
			<div class="bg-white dark:bg-background-dark border border-gray-100 dark:border-[#1d3a24] shadow-sm rounded-2xl p-6 max-w-sm">
				<p class="text-[10px] uppercase tracking-[0.3em] text-gray-400 font-bold mb-2">
					<?php
					$motto_label = tsm_get_theme_mod_cached( 'ministries_motto_label', 'Our Motto' );
					echo esc_html( $motto_label );
					?>
				</p>
				<p class="text-2xl text-primary dark:text-accent font-serif font-bold italic serif-text">
					<?php
					$motto_text = tsm_get_theme_mod_cached( 'ministries_motto_text', '"Win and Help Win"' );
					echo esc_html( $motto_text );
					?>
				</p>
			</div>
		</div>
	</div>
</section>

<!-- Mission Section -->
<section class="py-24 px-6 bg-white dark:bg-[#0a140d] border-y border-gray-100 dark:border-[#1d3a24]">
	<div class="max-w-[1280px] mx-auto px-6">
		<div class="grid grid-cols-1 lg:grid-cols-2 gap-20 items-center">
			<div class="space-y-12">
				<div>
					<h2 class="text-3xl text-primary serif-text dark:text-white mb-6">
						<?php
						$ministries_mission_title = tsm_get_theme_mod_cached( 'ministries_mission_title', 'Our mission is to walk with you as we restore Kingdom wealth.' );
						echo esc_html( $ministries_mission_title );
						?>
					</h2>
					<p class="text-lg text-gray-500 dark:text-gray-400 font-light leading-relaxed">
						<?php
						$ministries_mission_description = tsm_get_theme_mod_cached(
							'ministries_mission_description',
							'We believe that financial freedom is not just about individuals, but about equipping the entire body of Christ for the final harvest.'
						);
						echo esc_html( $ministries_mission_description );
						?>
					</p>
				</div>
				<div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
					<?php
					$ministries_pillars = tsm_get_theme_mod_cached(
						'ministries_pillars',
						'hub|Galvanizing the Church|Preparing the global body for the divine transfer of wealth through enterprise.|flare|Re-awakening Purpose|Raising a Joshua Generation focused on achieving Kingdom wealth for His glory.|handshake|Bridging the Gap|Restoring the essential family and community spirit that destroys systemic poverty.|shield|Equipping Every Saint|Providing the financial tools needed for the spiritual battle ahead.'
					);
					$pillars = explode( '|', $ministries_pillars );
					$pillar_count = count( $pillars ) / 3;
					for ( $i = 0; $i < $pillar_count; $i++ ) {
						$icon = isset( $pillars[ $i * 3 ] ) ? trim( $pillars[ $i * 3 ] ) : 'check';
						$title = isset( $pillars[ $i * 3 + 1 ] ) ? trim( $pillars[ $i * 3 + 1 ] ) : '';
						$description = isset( $pillars[ $i * 3 + 2 ] ) ? trim( $pillars[ $i * 3 + 2 ] ) : '';
						if ( empty( $title ) ) {
							continue;
						}
						?>
						<div class="group text-center sm:text-left">
							<div class="w-12 h-12 rounded-xl bg-primary/10 dark:bg-primary/20 flex items-center justify-center text-primary dark:text-accent group-hover:bg-primary group-hover:text-white transition-all mb-4 mx-auto sm:mx-0">
								<span class="material-symbols-outlined !text-3xl"><?php echo esc_html( $icon ); ?></span>
							</div>
							<h4 class="font-bold text-accent dark:text-white mb-2"><?php echo esc_html( $title ); ?></h4>
							<p class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed"><?php echo esc_html( $description ); ?></p>
						</div>
						<?php
					}
					?>
				</div>
			</div>
			<div class="relative">
				<div class="rounded-3xl overflow-hidden shadow-2xl rotate-2 hover:rotate-0 transition-transform duration-500">
					<?php
					$ministries_image = tsm_get_theme_mod_cached( 'ministries_image', get_template_directory_uri() . '/assets/images/ministry-work.jpg' );
					?>
					<img alt="Ministry Work" class="w-full aspect-[4/5] object-cover" src="<?php echo esc_url( $ministries_image ); ?>"/>
				</div>
				<div class="absolute -bottom-8 -left-8 bg-primary text-white p-8 rounded-2xl shadow-xl max-w-xs">
					<span class="material-symbols-outlined mb-2 opacity-50">format_quote</span>
					<p class="text-lg font-serif italic leading-relaxed serif-text">
						<?php
						$ministries_quote = tsm_get_theme_mod_cached(
							'ministries_quote',
							'"The church is rising to take her place in the economy of heaven."'
						);
						echo esc_html( $ministries_quote );
						?>
					</p>
				</div>
			</div>
		</div>
	</div>
</section>

<!-- Timeline Section -->
<section class="py-32 px-6 overflow-hidden">
	<div class="max-w-[1000px] mx-auto">
		<div class="text-center mb-24">
			<h2 class="text-3xl md:text-5xl font-black text-accent dark:text-white mb-4">
				<?php
				$timeline_title = tsm_get_theme_mod_cached( 'ministries_timeline_title', 'Our Journey Together' );
				echo esc_html( $timeline_title );
				?>
			</h2>
			<p class="text-gray-500 dark:text-gray-400">
				<?php
				$timeline_subtitle = tsm_get_theme_mod_cached( 'ministries_timeline_subtitle', 'How we practically bring the vision to life, step by step.' );
				echo esc_html( $timeline_subtitle );
				?>
			</p>
		</div>
		<div class="relative">
			<div class="absolute left-8 md:left-1/2 top-0 bottom-0 w-px bg-gradient-to-b from-transparent via-primary to-transparent -translate-x-1/2 hidden md:block"></div>
			<div class="space-y-16 relative">
				<?php
				$timeline_items = tsm_get_theme_mod_cached(
					'ministries_timeline_items',
					'payments|Wealth Creation Conferences|Intensive seminars sharing biblical strategies for generating and circulating wealth.|left|account_tree|REGAM Committees|Local support structures helping believers find stable, profitable employment.|right|volunteer_activism|"Helps" Ministry|Building financial foundations for fellow ministers to increase their reach.|left|model_training|Empowerment Workshops|Hands-on practical training specifically tailored for financial stewardship.|right|medical_services|Rural & Medical Outreaches|Healing bodies and sharing the Gospel in hard-to-reach communities.|left|psychology|Life & Career Coaching|One-on-one professional guidance for life development and career paths.|right|edit_note|Publishing Consultants|Mentoring authors and media creators to spread the Kingdom message.|left|support|Missions Supporters|A global network of financiers committed to funding gospel expansion.|right|local_library|Writers & Publishers|A community of storytellers dedicated to Christian media and books.|left|celebration|Soul Winning|The ultimate goal: leading every heart back to the Father.|right'
				);
				$items = explode( '|', $timeline_items );
				$item_count = count( $items ) / 4;
				for ( $i = 0; $i < $item_count; $i++ ) {
					$icon = isset( $items[ $i * 4 ] ) ? trim( $items[ $i * 4 ] ) : 'check';
					$title = isset( $items[ $i * 4 + 1 ] ) ? trim( $items[ $i * 4 + 1 ] ) : '';
					$description = isset( $items[ $i * 4 + 2 ] ) ? trim( $items[ $i * 4 + 2 ] ) : '';
					$alignment = isset( $items[ $i * 4 + 3 ] ) ? trim( $items[ $i * 4 + 3 ] ) : 'left';
					if ( empty( $title ) ) {
						continue;
					}
					$is_left = ( $alignment === 'left' );
					$bg_color = $is_left ? 'bg-primary' : 'bg-accent';
					?>
					<div class="flex flex-col md:flex-row items-center md:items-center gap-8 md:gap-0">
						<?php if ( $is_left ) : ?>
							<div class="flex-1 text-center md:text-right md:pr-12 order-2 md:order-1">
								<h4 class="text-xl font-bold text-primary dark:text-white mb-2"><?php echo esc_html( $title ); ?></h4>
								<p class="text-sm text-gray-500 dark:text-gray-400"><?php echo esc_html( $description ); ?></p>
							</div>
							<div class="w-16 h-16 rounded-full <?php echo esc_attr( $bg_color ); ?> text-white flex items-center justify-center z-10 order-1 md:order-2 shrink-0 md:shadow-[0_0_0_8px_rgba(0,0,0,0)] dark:md:shadow-[0_0_0_8px_rgba(0,0,0,0)]">
								<span class="material-symbols-outlined"><?php echo esc_html( $icon ); ?></span>
							</div>
							<div class="flex-1 hidden md:block order-3"></div>
						<?php else : ?>
							<div class="flex-1 hidden md:block order-1"></div>
							<div class="w-16 h-16 rounded-full <?php echo esc_attr( $bg_color ); ?> text-white flex items-center justify-center z-10 order-1 md:order-2 shrink-0 md:shadow-[0_0_0_8px_rgba(0,0,0,0)] dark:md:shadow-[0_0_0_8px_rgba(0,0,0,0)]">
								<span class="material-symbols-outlined"><?php echo esc_html( $icon ); ?></span>
							</div>
							<div class="flex-1 text-center md:text-left md:pl-12 order-2 md:order-3">
								<h4 class="text-xl font-bold text-primary dark:text-white mb-2"><?php echo esc_html( $title ); ?></h4>
								<p class="text-sm text-gray-500 dark:text-gray-400"><?php echo esc_html( $description ); ?></p>
							</div>
						<?php endif; ?>
					</div>
					<?php
				}
				?>
			</div>
		</div>
	</div>
</section>

<!-- Resources Section -->
<section class="py-24 px-6 bg-[#f3f7f4] dark:bg-[#0c1a11]">
	<div class="max-w-[1280px] px-6 mx-auto">
		<div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
			<div class="grid grid-cols-2 gap-4">
				<?php
				$resource_image_1 = tsm_get_theme_mod_cached( 'ministries_resource_image_1', get_template_directory_uri() . '/assets/images/book-1.jpg' );
				$resource_image_2 = tsm_get_theme_mod_cached( 'ministries_resource_image_2', get_template_directory_uri() . '/assets/images/book-2.jpg' );
				?>
				<img alt="Book" class="rounded-2xl shadow-lg -rotate-3" src="<?php echo esc_url( $resource_image_1 ); ?>"/>
				<img alt="Publication" class="rounded-2xl shadow-lg rotate-3 translate-y-12" src="<?php echo esc_url( $resource_image_2 ); ?>"/>
			</div>
			<div>
				<h2 class="text-4xl text-accent serif-text dark:text-white mb-8">
					<?php
					$resources_title = tsm_get_theme_mod_cached( 'ministries_resources_title', 'Equip your mind with truth.' );
					echo esc_html( $resources_title );
					?>
				</h2>
				<div class="space-y-4 mb-12">
					<?php
					$resource_items = tsm_get_theme_mod_cached(
						'ministries_resource_items',
						'menu_book|Books & Publications'
					);
					$res_items = explode( '|', $resource_items );
					$res_count = count( $res_items ) / 2;
					$books_page_id = tsm_get_theme_mod_cached( 'books_page_id', 0 );
					$books_url = $books_page_id ? get_permalink( $books_page_id ) : home_url( '/books' );
					for ( $i = 0; $i < $res_count; $i++ ) {
						$res_icon = isset( $res_items[ $i * 2 ] ) ? trim( $res_items[ $i * 2 ] ) : 'check';
						$res_text = isset( $res_items[ $i * 2 + 1 ] ) ? trim( $res_items[ $i * 2 + 1 ] ) : '';
						if ( empty( $res_text ) ) {
							continue;
						}
						$is_books_link = ( $res_text === 'Books & Publications' );
						?>
						<div class="flex items-center gap-4 p-4 bg-white dark:bg-background-dark rounded-xl shadow-sm border border-gray-100 dark:border-[#1d3a24]">
							<span class="material-symbols-outlined text-primary"><?php echo esc_html( $res_icon ); ?></span>
							<?php if ( $is_books_link ) : ?>
								<a href="<?php echo esc_url( $books_url ); ?>" class="font-medium text-accent dark:text-white hover:text-accent transition-colors">
									<?php echo esc_html( $res_text ); ?>
								</a>
							<?php else : ?>
								<span class="font-medium text-accent dark:text-white"><?php echo esc_html( $res_text ); ?></span>
							<?php endif; ?>
						</div>
						<?php
					}
					?>
				</div>
				<div class="bg-accent p-8 rounded-3xl text-white relative overflow-hidden group">
					<div class="absolute -right-8 -bottom-8 opacity-10 group-hover:scale-110 transition-transform">
						<span class="material-symbols-outlined !text-[120px]">auto_stories</span>
					</div>
					<p class="text-xs font-bold uppercase tracking-[0.3em] text-primary mb-4">
						<?php
						$bible_label = tsm_get_theme_mod_cached( 'ministries_bible_label', 'Our Foundation' );
						echo esc_html( $bible_label );
						?>
					</p>
					<h4 class="text-3xl font-serif mb-4 serif-text">
						<?php
						$bible_title = tsm_get_theme_mod_cached( 'ministries_bible_title', 'The Holy Bible' );
						echo esc_html( $bible_title );
						?>
					</h4>
					<p class="text-white/70 text-sm leading-relaxed max-w-sm">
						<?php
						$bible_description = tsm_get_theme_mod_cached(
							'ministries_bible_description',
							'The primary source of all our wisdom, strategy, and inspiration for the work of the ministry.'
						);
						echo esc_html( $bible_description );
						?>
					</p>
				</div>
			</div>
		</div>
	</div>
</section>

<!-- CTA Section -->
<section class="max-w-[1280px] mx-auto py-24 px-6">
	<div class="bg-primary text-white rounded-3xl p-12 md:p-20 text-center relative overflow-hidden">
		<?php
		$cta_bg_image = tsm_get_theme_mod_cached( 'ministries_cta_bg_image', '' );
		if ( $cta_bg_image ) {
			?>
			<div class="absolute inset-0 bg-cover bg-center opacity-10" style="background-image: url('<?php echo esc_url( $cta_bg_image ); ?>')"></div>
			<?php
		}
		?>
		<div class="relative z-10 max-w-3xl mx-auto">
			<div class="bg-white text-primary p-4 rounded-2xl mb-8 shadow-lg shadow-white/30 w-fit mx-auto">
				<span class="material-symbols-outlined !text-4xl">volunteer_activism</span>
			</div>
			<h2 class="text-3xl md:text-5xl font-black mb-6">
				<?php
				$cta_title = tsm_get_theme_mod_cached( 'ministries_cta_title', 'Ready to make a difference?' );
				echo esc_html( $cta_title );
				?>
			</h2>
			<p class="text-white/80 max-w-xl mx-auto mb-10 text-lg leading-relaxed">
				<?php
				$cta_description = tsm_get_theme_mod_cached(
					'ministries_cta_description',
					'Join the REGAM Global community and help us break the chains of poverty through faith and wise enterprise.'
				);
				echo esc_html( $cta_description );
				?>
			</p>
			<div class="flex flex-col sm:flex-row justify-center gap-4">
				<?php
				$partner_url = home_url( '/partners' );
				$contact_page_id = tsm_get_theme_mod_cached( 'contact_page_id', 0 );
				$contact_url = $contact_page_id ? get_permalink( $contact_page_id ) : home_url( '/contact-us' );
				?>
				 <a href="<?php echo esc_url( $partner_url ); ?>" target="_blank" rel="noopener" class="bg-white text-primary hover:text-accent hover:bg-gray-100 font-bold py-4 px-10 rounded-lg shadow-xl transition-all flex items-center gap-2">
					Become a Partner
				</a>
				<a href="<?php echo esc_url( $contact_url ); ?>" class="bg-transparent border-2 border-white/40 backdrop-blur-md text-white hover:text-white font-bold py-4 px-10 rounded-lg hover:bg-white/10 transition-all">
					Contact Us
				</a>
			</div>
		</div>
	</div>
</section>

<?php
get_footer();