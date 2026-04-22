<div class="fullscreen-search" id="fullscreen-search">
    <div class="fullscreen-search-header">
        <div class="fullscreen-search-logo">
            <a href="<?php echo esc_url(site_url()); ?>">
                <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/puk-logo.png" alt="puk-logo">
            </a>
        </div>
        <button class="search-close" aria-label="Close search">
            <svg xmlns="http://www.w3.org/2000/svg" width="31" height="31" viewBox="0 0 31 31" fill="none">
                <path d="M1.711 30.4887L0 28.7777L13.5333 15.2443L0 1.711L1.711 0L15.2443 13.5333L28.7777 0L30.4887 1.711L16.9553 15.2443L30.4887 28.7777L28.7777 30.4887L15.2443 16.9553L1.711 30.4887Z" fill="white" />
            </svg>
        </button>
    </div>

    <div class="search-center">
        <div class="search-box">
            <input type="text" name="s" id="puk-search-input" placeholder="Search products..." autocomplete="off">
            <button class="search-btn" type="button" aria-label="Search">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 27 27" fill="none">
                    <path d="M26.5 26.5L20.2268 20.2268M20.2268 20.2268C21.2999 19.1538 22.1511 17.8799 22.7318 16.4779C23.3125 15.0759 23.6114 13.5732 23.6114 12.0557C23.6114 10.5382 23.3125 9.03554 22.7318 7.63354C22.1511 6.23153 21.2999 4.95764 20.2268 3.88459C19.1538 2.81154 17.8799 1.96036 16.4779 1.37963C15.0759 0.798898 13.5732 0.5 12.0557 0.5C10.5382 0.5 9.03554 0.798898 7.63354 1.37963C6.23153 1.96036 4.95764 2.81154 3.88459 3.88459C1.71747 6.05171 0.5 8.99095 0.5 12.0557C0.5 15.1205 1.71747 18.0597 3.88459 20.2268C6.05171 22.394 8.99095 23.6114 12.0557 23.6114C15.1205 23.6114 18.0597 22.394 20.2268 20.2268Z" stroke="white" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </button>

            <div class="search-loader" id="search-loader">
                <div class="loader-ring">
                    <div class="loader-ring-light"></div>
                    <div class="loader-ring-track"></div>
                </div>
            </div>
        </div>

        <div class="search-suggestions" id="search-suggestions">
            <div class="suggestions-grid" id="suggestions-grid">
                <!-- Populated via AJAX -->
            </div>

            <div class="search-no-results" id="search-no-results" style="display: none;">
                <div class="no-results-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <circle cx="11" cy="11" r="8"></circle>
                        <path d="M21 21l-4.35-4.35"></path>
                        <path d="M8 8l6 6"></path>
                        <path d="M14 8l-6 6"></path>
                    </svg>
                </div>
                <p class="no-results-text">No products found</p>
                <p class="no-results-hint">Try different keywords or browse our categories</p>
            </div>
        </div>
    </div>
</div>
