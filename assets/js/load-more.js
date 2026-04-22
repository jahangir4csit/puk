jQuery(function ($) {
    $(document).on('click', '#puk-load-more, #puk-load-more-projects, #puk-load-more-media', function (e) {
        e.preventDefault();

        var button = $(this);
        var action = button.data('action') || 'load_more_posts';
        var container = button.data('container') || '#puk-posts-container';

        var data = {
            'action': action,
            'page': button.data('page'),
        };

        // Add optional parameters if they exist
        if (button.data('category')) {
            data.category = button.data('category');
        }

        $.ajax({
            url: ajax_object.ajax_url,
            data: data,
            type: 'POST',
            beforeSend: function () {
                button.text('Loading...');
                button.prop('disabled', true);
            },
            success: function (response) {
                if (response) {
                    $(container).append(response);

                    var currentPage = button.data('page');
                    var maxPages = button.data('max-pages');
                    var nextPage = currentPage + 1;

                    button.data('page', nextPage);
                    button.text('Load More');
                    button.prop('disabled', false);

                    if (nextPage >= maxPages) {
                        button.parent().remove();
                    }

                    // For project filtering compatibility
                    if (typeof window.pukApplyProjectFilter === 'function') {
                        window.pukApplyProjectFilter();
                    }
                } else {
                    button.parent().remove();
                }
            }
        });
    });
});
