/**
 * PDF Generator - Frontend JavaScript
 *
 * Handles the Data Sheet PDF download button functionality.
 *
 * @package PUK
 * @since 1.0.0
 */

(function($) {
    'use strict';

    /**
     * PDF Generator Module
     */
    var PUKPdfGenerator = {

        /**
         * Initialize
         */
        init: function() {
            this.bindEvents();
        },

        /**
         * Bind event handlers
         */
        bindEvents: function() {
            $(document).on('click', '.datasheet-pdf-btn', this.handleDownload.bind(this));
        },

        /**
         * Handle download button click
         *
         * @param {Event} e Click event
         */
        handleDownload: function(e) {
            e.preventDefault();
     
            var $button = $(e.currentTarget);
            var productId = $button.data('product-id');
            
            if (!productId) {
                console.error('PDF Generator: No product ID found');
                return;
            }

            // Prevent double clicks
            if ($button.hasClass('is-loading')) {
                return;
            }

            this.downloadPDF($button, productId);
        },

        /**
         * Download PDF via AJAX
         *
         * @param {jQuery} $button The button element
         * @param {number} productId Product ID
         */
        downloadPDF: function($button, productId) {
            var self = this;

            // Show loading state
            this.setLoadingState($button, true);

            // Create form and submit for direct download
            var form = document.createElement('form');
            form.method = 'POST';
            form.action = pukPdfGenerator.ajaxurl;
            form.style.display = 'none';

            // Add form fields
            var fields = {
                'action': 'puk_generate_product_pdf',
                'product_id': productId,
                'nonce': pukPdfGenerator.nonce
            };

            for (var key in fields) {
                var input = document.createElement('input');
                input.type = 'hidden';
                input.name = key;
                input.value = fields[key];
                form.appendChild(input);
            }

            // For browsers that don't support form-based PDF download,
            // use XMLHttpRequest with blob
            this.downloadViaXHR($button, productId);
        },

        /**
         * Download via XMLHttpRequest (supports blob)
         *
         * @param {jQuery} $button The button element
         * @param {number} productId Product ID
         */
        downloadViaXHR: function($button, productId) {
            var self = this;
            var xhr = new XMLHttpRequest();

            xhr.open('POST', pukPdfGenerator.ajaxurl, true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.responseType = 'blob';

            xhr.onload = function() {
                if (xhr.status === 200) {
                    // Check if response is PDF
                    var contentType = xhr.getResponseHeader('Content-Type');

                    if (contentType && contentType.indexOf('application/pdf') !== -1) {
                        // Get filename from Content-Disposition header
                        var filename = self.getFilenameFromHeader(xhr) || 'datasheet.pdf';

                        // Create download link
                        self.triggerDownload(xhr.response, filename);
                        self.setLoadingState($button, false);
                    } else {
                        // Response might be JSON error
                        var reader = new FileReader();
                        reader.onload = function() {
                            try {
                                var response = JSON.parse(reader.result);
                                if (!response.success) {
                                    self.showError($button, response.data.message || pukPdfGenerator.i18n.error);
                                }
                            } catch (e) {
                                self.showError($button, pukPdfGenerator.i18n.error);
                            }
                            self.setLoadingState($button, false);
                        };
                        reader.readAsText(xhr.response);
                    }
                } else {
                    self.showError($button, pukPdfGenerator.i18n.error);
                    self.setLoadingState($button, false);
                }
            };

            xhr.onerror = function() {
                self.showError($button, pukPdfGenerator.i18n.error);
                self.setLoadingState($button, false);
            };

            // Build form data
            var params = [
                'action=puk_generate_product_pdf',
                'product_id=' + encodeURIComponent(productId),
                'nonce=' + encodeURIComponent(pukPdfGenerator.nonce)
            ].join('&');

            xhr.send(params);
        },

        /**
         * Get filename from Content-Disposition header
         *
         * @param {XMLHttpRequest} xhr XHR object
         * @return {string|null} Filename or null
         */
        getFilenameFromHeader: function(xhr) {
            var disposition = xhr.getResponseHeader('Content-Disposition');

            if (disposition && disposition.indexOf('filename') !== -1) {
                var matches = disposition.match(/filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/);
                if (matches && matches[1]) {
                    return matches[1].replace(/['"]/g, '');
                }
            }

            return null;
        },

        /**
         * Trigger file download
         *
         * @param {Blob} blob PDF blob
         * @param {string} filename Filename
         */
        triggerDownload: function(blob, filename) {
            var url = window.URL.createObjectURL(blob);
            var a = document.createElement('a');

            a.style.display = 'none';
            a.href = url;
            a.download = filename;

            document.body.appendChild(a);
            a.click();

            // Cleanup
            setTimeout(function() {
                document.body.removeChild(a);
                window.URL.revokeObjectURL(url);
            }, 100);
        },

        /**
         * Set loading state on button
         *
         * @param {jQuery} $button Button element
         * @param {boolean} isLoading Loading state
         */
        setLoadingState: function($button, isLoading) {
            var $icon = $button.find('.pdf-icon-default');
            var $loading = $button.find('.pdf-loading');
            // Support both .text (product page) and .download-text (accordion)
            var $text = $button.find('.text, .download-text');

            if (isLoading) {
                $button.addClass('is-loading');
                $icon.hide();
                $loading.show();
                if ($text.length) {
                    $text.data('original-text', $text.text());
                    $text.text(pukPdfGenerator.i18n.generating);
                }
            } else {
                $button.removeClass('is-loading');
                $icon.show();
                $loading.hide();
                if ($text.length && $text.data('original-text')) {
                    $text.text($text.data('original-text'));
                }
            }
        },

        /**
         * Show error message
         *
         * @param {jQuery} $button Button element
         * @param {string} message Error message
         */
        showError: function($button, message) {
            // Remove any existing error
            $button.siblings('.pdf-error').remove();

            // Create error element
            var $error = $('<div class="pdf-error" style="color: #dc3545; font-size: 12px; margin-top: 5px;"></div>');
            $error.text(message);

            // Insert after button
            $button.after($error);

            // Auto remove after 5 seconds
            setTimeout(function() {
                $error.fadeOut(300, function() {
                    $(this).remove();
                });
            }, 5000);
        }
    };

    /**
     * Initialize on document ready
     */
    $(document).ready(function() {
        PUKPdfGenerator.init();
    });

})(jQuery);
