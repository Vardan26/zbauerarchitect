<?php
/**
 * Title: Footer
 * Slug: zbauerarchitect/footer
 * Categories: footer
 * Block Types: core/template-part/footer
 * Description: Footer with logo and navigation.
 *
 * @package WordPress
 */
?>

<!-- wp:group -->
<div class="wp-block-group footer text-sm">
    <!-- wp:group -->
    <div class="wp-block-group container">
        <!-- wp:group -->
        <div class="wp-block-group">
            <!-- wp:site-logo /-->
        </div>
        <!-- /wp:group -->

        <!-- wp:group -->
        <div class="wp-block-group content">

            <!-- wp:navigation {"menuSlug":"footer","overlayMenu":"never","className":"nav-menu text-sm","layout":{"type":"flex","orientation":"vertical"}} /-->

            <!-- wp:group -->
            <div class="wp-block-group flex column address">
                <!-- wp:paragraph -->
                <p class="bold">Office Address</p>
                <!-- /wp:paragraph -->

                <!-- wp:group -->
                <div class="wp-block-group flex address-content">
                        
                    <!-- wp:paragraph -->
                    <p><strong>Z Bauer Architects, LLC<br>Armenia, Yerevan</strong><br>3 Moskovyan Street<br>Loft co-working<br>Yerevan, 0009<br>Republic of Armenia<br>+374 94 452185</p>
                    <!-- /wp:paragraph -->

                    <!-- wp:paragraph -->
                    <p><strong>Z Bauer Architects, LLC<br>USA, Boston</strong><br>993 Massachusetts Avenue<br>Suite 215<br>Arlington, MA 02476-4519<br>USA</p>
                    <!-- /wp:paragraph -->
                </div>
                <!-- /wp:group -->
            </div>
            <!-- /wp:group -->

            <!-- wp:group {"layout":{"type":"default"}} -->
            <div class="wp-block-group social">
                <!-- wp:paragraph {"level":4} -->
                <p class="bold">Connect with Z Bauer Architects</p>
                <!-- /wp:paragraph -->

                <!-- wp:list -->
                <ul class="wp-block-group social-icons">
                  <li><a href="mailto:info@zbauer.com" target="_blank"><i class="fas fa-envelope"></i></a></li>
                  <li><a href="https://www.linkedin.com/company/z-bauer-architects/" target="_blank"><i class="fab fa-linkedin-in"></i></a></li>
                  <li><a href="https://www.instagram.com/zbauerarchitects/" target="_blank"><i class="fab fa-instagram"></i></a></li>
                  <li><a href="https://www.facebook.com/ZBauerArchitects" target="_blank"><i class="fab fa-facebook-f"></i></a></li>
                </ul>              
                <!-- /wp:group -->
            </div>
            <!-- /wp:group -->

            <!-- wp:list -->
            <ul class="list text-sm">
                <li><a href="/terms-of-use/">Terms of Use</a></li>
                <li><a href="/privacy-policy/">Privacy Policy</a></li>
            </ul>
            <!-- /wp:list -->
        </div>
        <!-- /wp:group -->
    </div>
    <!-- /wp:group -->

    <!-- wp:group -->
    <div class="wp-block-group copyright">
        <!-- wp:paragraph {"align":"center"} -->
        <p class="container">© COPYRIGHT <?php echo date('Y'); ?>. Z BAUER ARCHITECTS, LLC. ALL RIGHTS RESERVED.</p>
        <!-- /wp:paragraph -->
    </div>
    <!-- /wp:group -->
</div>
<!-- /wp:group -->