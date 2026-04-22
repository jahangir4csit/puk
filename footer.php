<footer class="footer">

    <div class="container">
        <div class="footer_wrap">
            <!-- Left Side -->
            <div class="footer_left">
            <div class="footer_left_cont">
    
        <div class="footer_left_cont_top">
    
          <!-- Left Menu -->
          <div class="footer_left_cont_left">
            <?php
            if (has_nav_menu('footer_menu')) {
                wp_nav_menu([
                    'theme_location' => 'footer_menu',
                    'container' => false,
                    'menu_class' => 'footer-menu',
                ]);
            } else {
                echo '<ul><li><a href="#">Add Footer Menu</a></li></ul>';
            }
            ?>
          </div>
    
          <!-- Follow + Center Logo -->
          <div class="footer_left_cont_right">
            <div class="footer_left_cont_right_flwus">
                <span>Follow us</span>
                <div class="footer_left_cont_smedia">
                    <?php
                      $social_links = get_field('footer_social_links', 'option');
                      if ($social_links) {
                          foreach ($social_links as $social) {
                              echo "<a href='" . esc_url($social['platform_url']) . "'>";
                              echo "<i class='" . esc_attr($social['platform_icon']) . "'></i>";
                              echo "</a>";
                          }
                      }
                    ?>
                </div>
            </div>
            <div class="footer_left_cont_right_cmpny">
                <?php if ($rlogo = get_field('footer_company_logo', 'option')): ?>
                    <img src="<?php echo esc_url($rlogo['url']); ?>" alt="<?php echo esc_attr($rlogo['alt']); ?>">
                <?php endif; ?>
            </div>
          </div>
        </div>
    
        <!-- Footer Bottom -->
        <div class="footer_left_cont_bottom">
          <div class="footer_bootm">
            <p><?php echo get_field('footer_company_info', 'option'); ?></p>
            <p class="small">
              <a href="<?php echo esc_url(get_field('footer_cookie_policy_url', 'option')); ?>">
                <?php echo esc_html(get_field('footer_cookie_policy_label', 'option')); ?>
              </a> |
              <a href="<?php echo esc_url(get_field('footer_privacy_policy_url', 'option')); ?>">
                <?php echo esc_html(get_field('footer_privacy_policy_label', 'option')); ?>
              </a>
            </p>
          </div>
          <div class="footer_bootm_2 hide_on_mobile">
                <span >Credits: <a href="https://www.red-apple.it/" target="_blank">Red Apple</a></span>
          </div>
        </div>
    
      </div>
        </div>
            <!-- Right Side -->
            <div class="footer_right">
                <div class="footer_right_cont">
                <div class="footer_right_contct">
                <p><?php echo get_field('footer_right_contact_text', 'option'); ?></p>
                <a href="#" class="btn btn-outline-light btn-sm rounded-0">Request a contact</a>
            </div>
            <div class="footer_right_subscribe">
                <p><?php echo get_field('footer_newsletter_text', 'option'); ?></p>
    		    <?php echo do_shortcode('[contact-form-7 id="f1f60a1" title="Newsletter"]'); ?>
            </div>
            </div>
        </div>
            <div class="footer_bootm_2 hide_on_desktop w-100">
                <span >Credits: <a href="https://www.red-apple.it/" target="_blank">Red Apple</a></span>
            </div>
        </div>
    </div>
      
</footer>
 
            <!-- lightbox_puk -->
            <div class="lightbox_puk" id="lightbox_puk">
                <button class="lightbox_puk-close" id="lightbox_puk-close">&times;</button>
                <button class="lightbox_puk-prev" id="lightbox_puk-prev">&#10094;</button>
                <button class="lightbox_puk-next" id="lightbox_puk-next">&#10095;</button>
                <div class="lightbox_puk-content">
                    <div class="lightbox_puk-loader" id="lightbox_puk-loader"></div>
                    <img class="lightbox_puk-image" id="lightbox_puk-image" src="" alt="">
                    <div class="lightbox_puk-video-wrap" id="lightbox_puk-video-wrap" style="display:none;">
                        <video class="lightbox_puk-video" id="lightbox_puk-video" controls playsinline></video>
                        <iframe class="lightbox_puk-iframe" id="lightbox_puk-iframe" src="" frameborder="0" allow="autoplay; fullscreen" allowfullscreen style="display:none;"></iframe>
                    </div>
                </div>
                <div class="lightbox_puk-counter" id="lightbox_puk-counter"></div>
            </div>



<script>

// document.addEventListener("DOMContentLoaded", function () {
//     const h1 = document.querySelector(".prjct_pg_dtls_1_lft h1");
//     const img = document.querySelector(".prjct_pg_dtls_1_right_img img");

//     function onScroll() {
//         const imgRect = img.getBoundingClientRect();
//         const imgMidpoint = imgRect.top + imgRect.height / 2;

//         if (imgMidpoint <= 0) {
//             h1.classList.add("stop-sticky");
//         } else {
//             h1.classList.remove("stop-sticky");
//         }
//     }

//     window.addEventListener("scroll", onScroll);
//     onScroll(); // run on load too
// }); 

</script>

<!-- full screen search -->  
<?php get_template_part('template-parts/fsh-search'); ?>
<?php wp_footer(); ?>
</body>
</html>