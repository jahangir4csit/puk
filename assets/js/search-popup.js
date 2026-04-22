(function ($) {
    "use strict";

    $(document).ready(function () {
        var $searchTrigger = $(".header-search");
        var $searchPopup = $("#fullscreen-search");
        var $searchClose = $(".search-close");
        var $searchInput = $("#puk-search-input");
        var $searchSuggestions = $("#search-suggestions");
        var $suggestionsGrid = $("#suggestions-grid");
        var $searchLoader = $("#search-loader");
        var $searchNoResults = $("#search-no-results");
        var $searchBtn = $searchPopup.find(".search-btn");

        var searchTimeout = null;
        var currentRequest = null;
        var DEBOUNCE_DELAY = 400;
        var MIN_SEARCH_LENGTH = 2;

        // Open Search
        $searchTrigger.on("click", function (e) {
            e.preventDefault();
            $searchPopup.addClass("active");
            $("body").addClass("search-opened");
            setTimeout(function () {
                $searchInput.focus();
            }, 500);
        });

        // Close Search
        $searchClose.on("click", function (e) {
            e.preventDefault();
            closeSearch();
        });

        // Close on Escape key
        $(document).on("keydown", function (e) {
            if (e.key === "Escape" && $searchPopup.hasClass("active")) {
                closeSearch();
            }
        });

        function closeSearch() {
            $searchPopup.removeClass("active has-suggestions");
            $("body").removeClass("search-opened");
            $searchSuggestions.hide();
            $suggestionsGrid.empty();
            $searchNoResults.hide();
            $searchInput.val("");
            hideLoader();

            if (currentRequest) {
                currentRequest.abort();
                currentRequest = null;
            }
            if (searchTimeout) {
                clearTimeout(searchTimeout);
                searchTimeout = null;
            }
        }

        // Search on input with debounce
        $searchInput.on("input", function () {
            var searchTerm = $(this).val().trim();

            if (searchTimeout) { clearTimeout(searchTimeout); }
            if (currentRequest) { currentRequest.abort(); currentRequest = null; }

            if (searchTerm.length < MIN_SEARCH_LENGTH) {
                $searchPopup.removeClass("has-suggestions");
                $searchSuggestions.fadeOut(200);
                $suggestionsGrid.empty();
                $searchNoResults.hide();
                hideLoader();
                return;
            }

            showLoader();
            searchTimeout = setTimeout(function () {
                performSearch(searchTerm);
            }, DEBOUNCE_DELAY);
        });

        // Search on button click
        $searchBtn.on("click", function () {
            var searchTerm = $searchInput.val().trim();
            if (searchTerm.length >= MIN_SEARCH_LENGTH) {
                showLoader();
                performSearch(searchTerm);
            }
        });

        // Search on Enter key
        $searchInput.on("keypress", function (e) {
            if (e.which === 13) {
                e.preventDefault();
                var searchTerm = $(this).val().trim();
                if (searchTerm.length >= MIN_SEARCH_LENGTH) {
                    showLoader();
                    performSearch(searchTerm);
                }
            }
        });

        // Perform AJAX search
        function performSearch(searchTerm) {
            var ajaxUrl = typeof puk_search_vars !== "undefined" ? puk_search_vars.ajax_url : ajax_object.ajax_url;

            if (!ajaxUrl) {
                console.error("AJAX URL not found");
                hideLoader();
                return;
            }

            currentRequest = $.ajax({
                url: ajaxUrl,
                type: "POST",
                dataType: "json",
                data: {
                    action: "puk_product_search",
                    search: searchTerm
                },
                success: function (response) {
                    hideLoader();

                    if (response && response.success && response.data) {
                        var hasResults = false;

                        if (response.data.mode === "text") {
                            hasResults = response.data.items && response.data.items.length > 0;
                        } else {
                            hasResults = (response.data.families && response.data.families.length > 0) ||
                                         (response.data.products && response.data.products.length > 0);
                        }

                        if (hasResults) {
                            renderResults(response.data);
                            $searchPopup.addClass("has-suggestions");
                            $searchNoResults.hide();
                            $searchSuggestions.show();
                        } else {
                            $suggestionsGrid.empty();
                            $searchPopup.addClass("has-suggestions");
                            $searchNoResults.show();
                            $searchSuggestions.show();
                        }
                    } else {
                        $suggestionsGrid.empty();
                        $searchPopup.addClass("has-suggestions");
                        $searchNoResults.show();
                        $searchSuggestions.show();
                    }
                },
                error: function (xhr, status, error) {
                    if (status !== "abort") {
                        hideLoader();
                        console.error("Search error:", status, error);
                        $suggestionsGrid.empty();
                        $searchPopup.addClass("has-suggestions");
                        $searchNoResults.show();
                        $searchSuggestions.show();
                    }
                },
                complete: function () {
                    currentRequest = null;
                }
            });
        }

        // Route to correct renderer based on mode
        function renderResults(data) {
            $suggestionsGrid.empty();
            if (data.mode === "text") {
                $suggestionsGrid.removeClass("suggestions-grid--code");
                renderTextResults(data.items);
            } else if (data.submode === 'dot') {
                $suggestionsGrid.addClass("suggestions-grid--code");
                renderCodeResultsDot(data.families, data.products);
            } else {
                $suggestionsGrid.removeClass("suggestions-grid--code");
                renderCodeResults(data.families, data.products);
            }
        }

        // Mode 1: row-per-item — left: term name, right: family code (always paired)
        function renderTextResults(items) {
            var html = '';

            $.each(items, function (i, item) {
                if (!item.family_code) return;
                var hideRow = item.depth <= 2
                    && items.slice(i + 1).some(function (next) {
                        return next.family_code === item.family_code;
                    });
                if (hideRow) return;
                html += '<div class="suggestions-grid__left"><div class="suggestion-item"><h4><a href="' + escapeHtml(item.permalink) + '">' + escapeHtml(item.title) + '</a></h4></div></div>';
                html += '<div class="suggestions-grid__right"><div class="suggestion-item"><h4><a href="' + escapeHtml(item.permalink) + '">' + escapeHtml(item.family_code) + '</a></h4></div></div>';
            });

            $suggestionsGrid.append(html);
        }

        // Mode 2: row-per-family (name left, code right)
        function renderCodeResults(families) {
            var html = '';

            $.each(families, function (i, family) {
                var code = family.family_code ? escapeHtml(family.family_code) : '...';
                html += '<div class="suggestions-grid__left"><div class="suggestion-item"><h4><a href="' + escapeHtml(family.permalink) + '">' + escapeHtml(family.title) + '</a></h4></div></div>';
                html += '<div class="suggestions-grid__right"><div class="suggestion-item"><h4><a href="' + escapeHtml(family.permalink) + '">' + code + '</a></h4></div></div>';
            });

            $suggestionsGrid.append(html);
        }

        // Dot-notation code search: two independent columns — left: families, right: SKUs
        function renderCodeResultsDot(families, products) {
            var leftHtml = '<div class="suggestions-grid__left">';
            var rightHtml = '<div class="suggestions-grid__right">';

            $.each(families, function (i, family) {
                leftHtml += '<div class="suggestion-item"><h4><a href="' + escapeHtml(family.permalink) + '">' + escapeHtml(family.title) + '</a></h4></div>';
            });

            $.each(products, function (i, product) {
                rightHtml += '<div class="suggestion-item"><h4><a href="' + escapeHtml(product.permalink) + '">' + escapeHtml(product.sku) + '</a></h4></div>';
            });

            leftHtml += '</div>';
            rightHtml += '</div>';

            $suggestionsGrid.append(leftHtml + rightHtml);
        }

        function showLoader() {
            $searchLoader.addClass("active");
            $searchBtn.addClass("hidden");
        }

        function hideLoader() {
            $searchLoader.removeClass("active");
            $searchBtn.removeClass("hidden");
        }

        function escapeHtml(text) {
            if (!text) return "";
            var div = document.createElement("div");
            div.textContent = text;
            return div.innerHTML;
        }
    });
})(jQuery);
