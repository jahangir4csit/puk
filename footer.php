<footer class="footer">

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
      <div class="footer_bootm_2">
            <span >Credits: <a href="#" >Red Apple</a></span>
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

</footer>
 
<!-- full screen search -->  
    
                <div class="fullscreen-search" id="fullscreen-search">
              <div class="fullscreen-search-header">
                	 <div class="fullscreen-search-logo">
        <a href="<?php echo site_url(); ?>">
            <img src="<?php echo site_url(); ?>/wp-content/themes/puk/assets/images/puk-logo.png" alt="puk-logo">
        </a>
    </div>
    <button class="search-close"><svg xmlns="http://www.w3.org/2000/svg" width="31" height="31" viewBox="0 0 31 31" fill="none">
  <path d="M1.711 30.4887L0 28.7777L13.5333 15.2443L0 1.711L1.711 0L15.2443 13.5333L28.7777 0L30.4887 1.711L16.9553 15.2443L30.4887 28.7777L28.7777 30.4887L15.2443 16.9553L1.711 30.4887Z" fill="white"/>
</svg></button>
            </div>
    <div class="search-center">
       <div class="search-box">
			<input type="text" name="s" placeholder="Search here" autocomplete="off">
			<button class="search-btn" type="submit">
				<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 27 27" fill="none">
				  <path d="M26.5 26.5L20.2268 20.2268M20.2268 20.2268C21.2999 19.1538 22.1511 17.8799 22.7318 16.4779C23.3125 15.0759 23.6114 13.5732 23.6114 12.0557C23.6114 10.5382 23.3125 9.03554 22.7318 7.63354C22.1511 6.23153 21.2999 4.95764 20.2268 3.88459C19.1538 2.81154 17.8799 1.96036 16.4779 1.37963C15.0759 0.798898 13.5732 0.5 12.0557 0.5C10.5382 0.5 9.03554 0.798898 7.63354 1.37963C6.23153 1.96036 4.95764 2.81154 3.88459 3.88459C1.71747 6.05171 0.5 8.99095 0.5 12.0557C0.5 15.1205 1.71747 18.0597 3.88459 20.2268C6.05171 22.394 8.99095 23.6114 12.0557 23.6114C15.1205 23.6114 18.0597 22.394 20.2268 20.2268Z" 
				  stroke="white" stroke-linecap="round" stroke-linejoin="round"/>
				</svg>
			</button>
        </div>

        <div class="search-suggestions" style="display: none;">
            <div class="suggestions-grid">
                <!-- Row 1 -->
                <div class="suggestion-item">
                    <div class="suggestion-img">
                        <a href="#">
                        <img src="https://puk.dominiotest.ch/wp-content/uploads/2025/11/101302-acceso-terra-4.jpg" alt="Ring Mega DMX RGBW">
                        </a>
                    </div>
                    <div class="suggestion-info">
                        <h3><a href="#">Ring Mega DMX RGBW</a></h3>
                        <p><a href="#">Floodlight</a></p>
                    </div>
                </div>
                <div class="suggestion-item">
                    <div class="suggestion-img">
                        <a href="#"><img src="https://puk.dominiotest.ch/wp-content/uploads/2025/11/101303-acceso-terra-3.jpg" alt="Ring Mega HP"></a>
                    </div>
                    <div class="suggestion-info">
                        <h3><a href="#">Ring Mega HP</a></h3>
                        <p><a href="#">Floodlight</a></p>
                    </div>
                </div>
                <div class="suggestion-item">
                    <div class="suggestion-img">
                        <a href="#"><img src="https://puk.dominiotest.ch/wp-content/uploads/2025/11/101304-acceso-terra-3.jpg" alt="Ring Mega Cob"></a>
                    </div>
                    <div class="suggestion-info">
                        <h3><a href="#">Ring Mega Cob</a></h3>
                        <p><a href="#">Floodlight</a></p>
                    </div>
                </div>

                <!-- Row 2 -->
                <div class="suggestion-item">
                    <div class="suggestion-img">
                        <a href="#"><img src="https://puk.dominiotest.ch/wp-content/uploads/2025/11/101302-acceso-terra-10.jpg" alt="Ring Pole Medium Hp"></a>
                    </div>
                    <div class="suggestion-info">
                        <h3><a href="#">Ring Pole Medium Hp</a></h3>
                        <p><a href="#">Urban</a></p>
                    </div>
                </div>
                <div class="suggestion-item">
                    <div class="suggestion-img">
                        <a href="#"><img src="https://puk.dominiotest.ch/wp-content/uploads/2025/12/RING_01-1-1.jpg" alt="Ring Mega HP"></a>
                    </div>
                    <div class="suggestion-info">
                        <h3><a href="#">Ring Mega HP</a></h3>
                        <p><a href="#">Floodlight</a></p>
                    </div>
                </div>
                <div class="suggestion-item blog-item">
                    <div class="suggestion-content">
                        <h3><a href="#">Ring Mega: The maximum Power of Ring</a></h3>
                    </div>
                    <div class="suggestion-info">
                        <h3 class="d-none">Ring Mega</h3> <!-- Keep structure similar -->
                        <p><a href="#">Blog</a></p>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
                

<?php wp_footer(); ?>
</body>
</html>