<?php
/**
 * Title: Video-Banner 
 * Slug: zbauerarchitect/banner-video
 * Categories: banner
 * Block Types: core/template-part/banner-video
 * Description: Intro Banner with video.
 *
 * @package WordPress
 */
?>
<!-- wp:group -->
<div class="wp-block-group banner">

	<!-- wp:video {"className":"wp-block-image banner-image"} -->
	<figure class="wp-block-video wp-block-image banner-image">
		<video 
			autoplay
			loop 
			muted 
			preload="auto" 
			src="../assets/images/banner-loop.mp4" 
			playsinline 
			poster="../assets/images/banner-thumbnail.jpg"
		/>
	</figure>
	<!-- /wp:video -->

		
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