<?php
/**
 * Template part for displaying the gallery lightbox modal
 *
 * @package TSM_Theme
 *
 * @param array $args {
 *     Optional. Array of arguments.
 *     @type string $title        Gallery title to display in header
 *     @type string $location     Location text to display
 *     @type array  $images       Array of image data with 'full', 'thumb', 'alt' keys
 *     @type string $lightbox_id  Unique ID for this lightbox instance (default: 'gallery-lightbox')
 * }
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$title = isset( $args['title'] ) ? $args['title'] : '';
$location = isset( $args['location'] ) ? $args['location'] : '';
$images = isset( $args['images'] ) ? $args['images'] : array();
$lightbox_id = isset( $args['lightbox_id'] ) ? $args['lightbox_id'] : 'gallery-lightbox';

if ( empty( $images ) ) {
	return;
}
?>

<!-- Gallery Lightbox Modal -->
<div id="<?php echo esc_attr( $lightbox_id ); ?>" class="tsm-lightbox fixed inset-0 z-[100] bg-[#0a120c]/95 backdrop-blur-sm flex-col text-white hidden" style="display: none;" data-images='<?php echo esc_attr( wp_json_encode( $images ) ); ?>'>
	<!-- Header -->
	<div class="absolute top-0 left-0 right-0 h-20 flex items-center justify-between px-8 z-50">
		<div class="flex flex-col">
			<?php if ( $title ) : ?>
				<span class="text-xs font-bold uppercase tracking-[0.2em] text-primary"><?php echo esc_html( $title ); ?></span>
			<?php endif; ?>
			<span class="tsm-lightbox-counter text-[10px] text-gray-400 uppercase tracking-widest mt-0.5">Image 1 of <?php echo count( $images ); ?></span>
		</div>
		<div class="flex items-center gap-4">
			<a class="tsm-lightbox-download flex items-center justify-center size-10 rounded-full hover:bg-white/10 transition-colors" href="#" download>
				<span class="material-symbols-outlined text-[24px]">download</span>
			</a>
			<button class="tsm-lightbox-close flex items-center justify-center size-12 rounded-full bg-white/5 hover:bg-white/10 transition-colors border border-white/10">
				<span class="material-symbols-outlined text-[32px]">close</span>
			</button>
		</div>
	</div>
	
	<!-- Main Image Area -->
	<div class="flex-1 relative flex items-center justify-center p-4 md:p-12">
		<button class="tsm-lightbox-prev absolute left-6 z-20 group flex items-center justify-center size-16 rounded-full bg-white/5 backdrop-blur-md border border-white/10 hover:bg-white/20 hover:scale-105 transition-all">
			<span class="material-symbols-outlined text-[40px] text-white/70 group-hover:text-white">chevron_left</span>
		</button>
		
		<div class="relative max-w-6xl w-full h-full flex flex-col items-center justify-center gap-6">
			<div class="relative w-full h-full flex items-center justify-center">
				<img class="tsm-lightbox-image max-h-full max-w-full object-contain shadow-[0_25px_50px_-12px_rgba(0,0,0,0.8)] rounded-sm" src="" alt=""/>
			</div>
			<div class="w-full max-w-3xl text-center pb-4">
				<h3 class="tsm-lightbox-title text-2xl font-semibold tracking-tight"></h3>
				<?php if ( $location ) : ?>
					<div class="flex items-center justify-center gap-2 mt-2 text-gray-400 text-sm font-medium uppercase tracking-wider">
						<span class="material-symbols-outlined text-sm">location_on</span>
						<span class="tsm-lightbox-location"><?php echo esc_html( $location ); ?></span>
					</div>
				<?php endif; ?>
			</div>
		</div>
		
		<button class="tsm-lightbox-next absolute right-6 z-20 group flex items-center justify-center size-16 rounded-full bg-white/5 backdrop-blur-md border border-white/10 hover:bg-white/20 hover:scale-105 transition-all">
			<span class="material-symbols-outlined text-[40px] text-white/70 group-hover:text-white">chevron_right</span>
		</button>
	</div>
	
	<!-- Thumbnail Strip -->
	<div class="h-32 bg-black/40 backdrop-blur-xl border-t border-white/5 flex flex-col items-center justify-center">
		<div class="max-w-4xl w-full px-8 overflow-x-auto flex items-center gap-3 hide-scrollbar py-4 tsm-lightbox-thumbnails">
			<?php foreach ( $images as $index => $img ) :
				$thumb_url = isset( $img['thumb'] ) ? $img['thumb'] : ( isset( $img['full'] ) ? $img['full'] : '' );
				$full_url = isset( $img['full'] ) ? $img['full'] : '';
				$img_alt = isset( $img['alt'] ) ? $img['alt'] : '';
				?>
				<div class="tsm-lightbox-thumbnail flex-shrink-0 size-16 rounded-sm opacity-40 hover:opacity-100 cursor-pointer overflow-hidden transition-all <?php echo $index === 0 ? 'ring-2 ring-primary ring-offset-2 ring-offset-black scale-105 opacity-100' : ''; ?>" data-index="<?php echo $index; ?>" data-full="<?php echo esc_url( $full_url ); ?>" data-alt="<?php echo esc_attr( $img_alt ); ?>">
					<img class="w-full h-full object-cover" src="<?php echo esc_url( $thumb_url ); ?>" alt="<?php echo esc_attr( $img_alt ); ?>" loading="lazy" decoding="async"/>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</div>
