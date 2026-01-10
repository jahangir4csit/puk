(function ($) {
    "use strict";

    $(document).ready(function () {
        const $searchTrigger = $(".header-search");
        const $searchPopup = $("#fullscreen-search");
        const $searchClose = $(".search-close");
        const $searchInput = $searchPopup.find('input[type="text"]');

        const $searchSuggestions = $(".search-suggestions");

        // Open Search
        $searchTrigger.on("click", function (e) {
            e.preventDefault();
            $searchPopup.addClass("active");
            $("body").addClass("search-opened");
            setTimeout(() => {
                $searchInput.focus();
            }, 500);
        });

        // Close Search
        $searchClose.on("click", function (e) {
            e.preventDefault();
            $searchPopup.removeClass("active has-suggestions");
            $("body").removeClass("search-opened");
            $searchSuggestions.hide(); // Hide suggestions on close
            $searchInput.val(""); // Clear input on close
        });

        // Toggle Suggestions on Type
        $searchInput.on("input", function () {
            const val = $(this).val().trim();
            if (val.length > 0) {
                $searchPopup.addClass("has-suggestions");
                $searchSuggestions.fadeIn(300);
            } else {
                $searchPopup.removeClass("has-suggestions");
                $searchSuggestions.fadeOut(200);
            }
        });
    });
})(jQuery);
