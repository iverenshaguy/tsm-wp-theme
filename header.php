<?php
/**
 * The header template file
 *
 * @package TSM_Theme
 */
?>
<!DOCTYPE html>
<html class="light" <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>

<body <?php body_class( 'bg-background-light dark:bg-background-dark text-[#0d1b11] dark:text-white transition-colors duration-300' ); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site">
	<!-- Mobile Menu Toggle Checkbox -->
	<input class="hidden peer" id="mobile-menu-toggle" type="checkbox"/>
	
	<header class="sticky top-0 z-50 w-full border-b border-solid border-[#e7f3ea] dark:border-[#1d3a24] bg-background-light/80 dark:bg-background-dark/80 backdrop-blur-md">
		<div class="max-w-[1280px] mx-auto flex items-center justify-between px-6 py-4">
			<div class="flex items-center gap-3">
				<?php if ( has_custom_logo() ) : ?>
					<div class="text-primary dark:text-accent w-40 h-20">
						<?php the_custom_logo(); ?>
					</div>
				<?php else : ?>
					<div class="text-accent dark:text-primaryt size-8">
						<svg fill="none" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
							<path clip-rule="evenodd" d="M39.475 21.6262C40.358 21.4363 40.6863 21.5589 40.7581 21.5934C40.7876 21.655 40.8547 21.857 40.8082 22.3336C40.7408 23.0255 40.4502 24.0046 39.8572 25.2301C38.6799 27.6631 36.5085 30.6631 33.5858 33.5858C30.6631 36.5085 27.6632 38.6799 25.2301 39.8572C24.0046 40.4502 23.0255 40.7407 22.3336 40.8082C21.8571 40.8547 21.6551 40.7875 21.5934 40.7581C21.5589 40.6863 21.4363 40.358 21.6262 39.475C21.8562 38.4054 22.4689 36.9657 23.5038 35.2817C24.7575 33.2417 26.5497 30.9744 28.7621 28.762C30.9744 26.5497 33.2417 24.7574 35.2817 23.5037C36.9657 22.4689 38.4054 21.8562 39.475 21.6262ZM4.41189 29.2403L18.7597 43.5881C19.8813 44.7097 21.4027 44.9179 22.7217 44.7893C24.0585 44.659 25.5148 44.1631 26.9723 43.4579C29.9052 42.0387 33.2618 39.5667 36.4142 36.4142C39.5667 33.2618 42.0387 29.9052 43.4579 26.9723C44.1631 25.5148 44.659 24.0585 44.7893 22.7217C44.9179 21.4027 44.7097 19.8813 43.5881 18.7597L29.2403 4.41187C27.8527 3.02428 25.8765 3.02573 24.2861 3.36776C22.6081 3.72863 20.7334 4.58419 18.8396 5.74801C16.4978 7.18716 13.9881 9.18353 11.5858 11.5858C9.18354 13.988 7.18717 16.4978 5.74802 18.8396C4.58421 20.7334 3.72865 22.6081 3.36778 24.2861C3.02574 25.8765 3.02429 27.8527 4.41189 29.2403Z" fill="currentColor" fill-rule="evenodd"></path>
						</svg>
					</div>
				<?php endif; ?>
			</div>
			
			<!-- Desktop Navigation - Hidden below lg breakpoint -->
			<nav class="hidden lg:flex items-center gap-6 px-4">
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'primary',
						'menu_id'        => 'primary-menu',
						'container'      => false,
						'menu_class'     => 'flex items-center gap-6',
						'fallback_cb'    => false,
						'link_before'    => '',
						'link_after'     => '',
						'items_wrap'     => '<ul class="%2$s">%3$s</ul>',
						'walker'         => new TSM_Nav_Walker(),
					)
				);
				?>
			</nav>
			
			<div class="flex items-center gap-4">
				<!-- Desktop Partner Button - Hidden below lg breakpoint -->
				<a href="<?php echo esc_url( home_url( '/partners' ) ); ?>" class="hidden lg:flex min-w-[120px] cursor-pointer items-center justify-center rounded-lg h-10 px-6 bg-primary dark:bg-accent text-white hover:text-white text-sm font-bold shadow-lg shadow-primary/20 hover:shadow-primary/40 hover:scale-105 transition-all active:scale-95">
					<span>Partner With Us</span>
				</a>
				
				<!-- Mobile Menu Toggle Button - Visible below lg breakpoint -->
				<label class="lg:hidden cursor-pointer p-2 z-[70]" for="mobile-menu-toggle">
					<span class="material-symbols-outlined mobile-menu-icon text-3xl text-primary dark:text-white">menu</span>
					<span class="material-symbols-outlined mobile-close-icon text-3xl text-white hidden">close</span>
				</label>
			</div>
		</div>
	</header>
	
	<!-- Backdrop overlay -->
	<div class="lg:hidden fixed inset-0 bg-black/50 z-[99998] opacity-0 invisible transition-opacity duration-300" id="mobile-nav-backdrop"></div>
	
	<!-- Mobile Navigation Menu - Sidebar style, never full screen -->
	<div class="lg:hidden fixed top-0 bottom-0 right-0 bg-white dark:bg-background-dark z-[99999] transform translate-x-full transition-transform duration-300 ease-in-out overflow-hidden w-[70%] max-w-sm shadow-2xl flex flex-col" id="mobile-nav">
		<!-- Menu Header with Close Button -->
		<div class="flex-shrink-0 bg-white dark:bg-background-dark border-b border-[#e7f3ea] dark:border-[#1d3a24] px-6 py-4 flex items-center justify-between">
			<div class="flex items-center gap-3">
				<?php if ( has_custom_logo() ) : ?>
					<div class="text-primary dark:text-accent w-32 h-16">
						<?php the_custom_logo(); ?>
					</div>
				<?php else : ?>
					<div class="text-primary dark:text-accent size-6">
						<svg fill="none" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
							<path clip-rule="evenodd" d="M39.475 21.6262C40.358 21.4363 40.6863 21.5589 40.7581 21.5934C40.7876 21.655 40.8547 21.857 40.8082 22.3336C40.7408 23.0255 40.4502 24.0046 39.8572 25.2301C38.6799 27.6631 36.5085 30.6631 33.5858 33.5858C30.6631 36.5085 27.6632 38.6799 25.2301 39.8572C24.0046 40.4502 23.0255 40.7407 22.3336 40.8082C21.8571 40.8547 21.6551 40.7875 21.5934 40.7581C21.5589 40.6863 21.4363 40.358 21.6262 39.475C21.8562 38.4054 22.4689 36.9657 23.5038 35.2817C24.7575 33.2417 26.5497 30.9744 28.7621 28.762C30.9744 26.5497 33.2417 24.7574 35.2817 23.5037C36.9657 22.4689 38.4054 21.8562 39.475 21.6262ZM4.41189 29.2403L18.7597 43.5881C19.8813 44.7097 21.4027 44.9179 22.7217 44.7893C24.0585 44.659 25.5148 44.1631 26.9723 43.4579C29.9052 42.0387 33.2618 39.5667 36.4142 36.4142C39.5667 33.2618 42.0387 29.9052 43.4579 26.9723C44.1631 25.5148 44.659 24.0585 44.7893 22.7217C44.9179 21.4027 44.7097 19.8813 43.5881 18.7597L29.2403 4.41187C27.8527 3.02428 25.8765 3.02573 24.2861 3.36776C22.6081 3.72863 20.7334 4.58419 18.8396 5.74801C16.4978 7.18716 13.9881 9.18353 11.5858 11.5858C9.18354 13.988 7.18717 16.4978 5.74802 18.8396C4.58421 20.7334 3.72865 22.6081 3.36778 24.2861C3.02574 25.8765 3.02429 27.8527 4.41189 29.2403Z" fill="currentColor" fill-rule="evenodd"></path>
						</svg>
					</div>
				<?php endif; ?>
				<?php if ( ! has_custom_logo() ) : ?>
					<h2 class="text-[#0d1b11] dark:text-white text-lg font-black leading-tight tracking-tight uppercase">
						<?php bloginfo( 'name' ); ?>
					</h2>
				<?php endif; ?>
			</div>
			<button type="button" class="close-mobile-menu p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors" aria-label="Close menu">
				<span class="material-symbols-outlined text-2xl text-[#0d1b11] dark:text-white">close</span>
			</button>
		</div>
		
		<!-- Menu Content - Only nav area scrolls -->
		<div class="flex-1 flex flex-col overflow-hidden">
			<!-- Scrollable menu area -->
			<nav class="flex-1 overflow-y-auto min-h-0 w-full">
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'primary',
						'menu_id'        => 'mobile-menu',
						'container'      => false,
						'menu_class'     => 'w-full',
						'fallback_cb'    => false,
						'items_wrap'     => '<ul class="w-full">%3$s</ul>',
						'walker'         => new TSM_Mobile_Menu_Walker(),
					)
				);
				?>
			</nav>
			
			<!-- Fixed footer - never scrolls -->
			<div class="flex-shrink-0 mt-6 pt-4 px-6 border-t border-[#e7f3ea] dark:border-[#1d3a24]">
				<a href="<?php echo esc_url( home_url( '/partners' ) ); ?>" class="w-full bg-primary dark:bg-accent text-white py-3 rounded-lg font-bold text-sm shadow-lg shadow-primary/20 hover:shadow-primary/40 hover:scale-[1.02] transition-all flex items-center justify-center">
					Partner with Us
				</a>
				<p class="text-center text-gray-500 dark:text-gray-400 text-xs my-4 uppercase tracking-widest font-semibold">
					© <?php echo esc_html( date( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?>
				</p>
			</div>
		</div>
	</div>

	<main class="flex-grow">
