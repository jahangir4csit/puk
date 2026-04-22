/**
 * Accordion Filter
 * Handles the product filter inside family-accordion:
 *  - filter-acc-title click (open/close filter groups)
 *  - filter_input change   (apply filters + cascade)
 */
(function ($) {

    var filterTypes = ['watt', 'cct', 'beam', 'lumens', 'finish', 'dimming'];

    function applyFilters($filterBox, categoryNumber) {

        // 1. Collect active filters grouped by type
        var filters = {};
        $filterBox.find('.filter_input:checked').each(function () {
            var val  = $(this).val();
            var type = $(this).closest('.single-filter-accordion').data('filter-type');
            if (!filters[type]) filters[type] = [];
            filters[type].push(String(val));
        });

        // 2. Show/hide product rows (AND across types, OR within same type)
        var $tbody = $('.show_product_table_' + categoryNumber);
        $tbody.find('tr.product-row').each(function () {
            var $row         = $(this);
            var $downloadRow = $row.next('tr.download-row');
            var visible      = true;

            $.each(filters, function (type, vals) {
                var rowVal = String($row.data(type));
                if (vals.length && vals.indexOf(rowVal) === -1) {
                    visible = false;
                    return false; // break
                }
            });

            $row.toggle(visible);
            if (!visible) {
                $downloadRow.hide();
            } else if ($row.find('.accordion-data-btn').hasClass('active')) {
                $downloadRow.show();
            }
        });

        // 3. Collect values present in visible rows (for cascading)
        var available = {};
        filterTypes.forEach(function (type) { available[type] = {}; });

        $tbody.find('tr.product-row:visible').each(function () {
            var $row = $(this);
            filterTypes.forEach(function (type) {
                var val = String($row.data(type));
                if (val && val !== 'undefined' && val !== '') {
                    available[type][val] = true;
                }
            });
        });

        // 4. Cascade: show only options that exist in visible rows.
        //    Always keep checked options visible so the user can deselect them.
        $filterBox.find('.single-filter-accordion').each(function () {
            var $group = $(this);
            var type   = $group.data('filter-type');

            $group.find('.form-check').each(function () {
                var $check      = $(this);
                var $input      = $check.find('.filter_input');
                var val         = String($input.val());
                var isChecked   = $input.is(':checked');
                var isAvailable = available[type] && available[type][val];

                $check.toggle(isChecked || !!isAvailable);
            });
        });

        // 5. No-results message
        var $noResults = $tbody.find('.no-filter-results');
        if ($noResults.length === 0) {
            $noResults = $('<tr class="no-filter-results"><td colspan="9">No product variant found</td></tr>');
            $tbody.append($noResults);
        }
        $noResults.toggle($tbody.find('tr.product-row:visible').length === 0);

        // 6. Keep groups with active selections expanded
        $filterBox.find('.single-filter-accordion').each(function () {
            var $group   = $(this);
            var $title   = $group.find('.filter-acc-title');
            var $content = $group.find('.filter-acc-content');
            if ($group.find('.filter_input:checked').length > 0) {
                $title.addClass('active');
                $content.stop(true, true).slideDown(200);
            }
        });
    }

    $(document).ready(function () {

        // Filter accordion title — open / close a filter group
        $('body').on('click', '.filter-acc-title', function () {
            var $this    = $(this);
            var $content = $this.next('.filter-acc-content');

            // Close other groups that have no checked inputs
            $('.filter-acc-title').not($this).each(function () {
                var $otherTitle   = $(this);
                var $otherContent = $otherTitle.next('.filter-acc-content');
                if ($otherContent.find('.filter_input:checked').length === 0) {
                    $otherTitle.removeClass('active');
                    $otherContent.slideUp(200);
                }
            });

            // Toggle the clicked group
            $this.toggleClass('active');
            $content.stop(true, true).slideToggle(200);
        });

        // Checkbox change — apply filters
        $('body').on('change', '.filter_input', function () {
            var $filterBox     = $(this).closest('[class*="filter_category_item_"]');
            var parentClass    = $filterBox.attr('class') || '';
            var match          = parentClass.match(/filter_category_item_(\d+)/);
            var categoryNumber = match ? match[1] : null;
            if (!categoryNumber) return;

            applyFilters($filterBox, categoryNumber);
        });

    });

})(jQuery);
