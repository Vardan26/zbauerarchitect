<?php
/**
 * Title: Banner
 * Slug: zbauerarchitect/banner
 * Categories: banner
 * Block Types: core/template-part/banner
 * Description: Intro Banner.
 *
 * @package WordPress
 */
?>
<!-- wp:group -->
<div class="wp-block-group banner">
	<!-- wp:image -->
	<figure class="wp-block-image size-full banner-image">
		<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/banner.jpg' ); ?>" alt="Banner image" />
	</figure>
	<!-- /wp:image -->
	<!-- wp:group -->
	<div class="wp-block-group content">
		<!-- wp:group -->
		<div class="wp-block-group">
			<!-- wp:heading {"level":1,"className":"title-banner"} -->
			<h1 class="title-banner">designing today, shaping tomorrow*</h1>
			<!-- /wp:heading -->
			<!-- wp:paragraph -->
			<p class="text-sm">* Our mission is to enhance lifestyles through architecture that prioritizes energy-saving materials and ecological solutions.</p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:group -->
		<!-- wp:group -->
		<div class="wp-block-group ">
			<!-- wp:image {"id":0,"sizeSlug":"large","linkDestination":"none"} -->
			<figure class="wp-block-image size-large banner-content-image">
				<img src="" alt="Placeholder image">
			</figure>
			<!-- /wp:image -->
		</div>
		<!-- /wp:group -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->