// Gallery Popup Slider
jQuery(document).ready(function ($) {
    // Gallery variables
    let currentImageIndex = 0;
    let galleryImages = [];
    const popup = $('#galleryPopup');
    const popupImage = $('#popupImage');
    const currentIndexSpan = $('#currentIndex');
    const totalImagesSpan = $('#totalImages');

    // Collect all gallery images
    $('.gallery-item').each(function () {
        const imgSrc = $(this).find('img').attr('src');
        galleryImages.push(imgSrc);
    });

    // Update total images count
    totalImagesSpan.text(galleryImages.length);

    // Open popup when gallery item is clicked
    $('.gallery-item').on('click', function () {
        currentImageIndex = parseInt($(this).data('index'));
        openPopup(currentImageIndex);
    });

    // Close popup
    $('.popup-close, .popup-overlay').on('click', function () {
        closePopup();
    });

    // Previous image
    $('.popup-prev').on('click', function (e) {
        e.stopPropagation();
        showPrevImage();
    });

    // Next image
    $('.popup-next').on('click', function (e) {
        e.stopPropagation();
        showNextImage();
    });

    // Keyboard navigation
    $(document).on('keydown', function (e) {
        if (popup.hasClass('active')) {
            if (e.key === 'Escape') {
                closePopup();
            } else if (e.key === 'ArrowLeft') {
                showPrevImage();
            } else if (e.key === 'ArrowRight') {
                showNextImage();
            }
        }
    });

    // Functions
    function openPopup(index) {
        currentImageIndex = index;
        popupImage.attr('src', galleryImages[currentImageIndex]);
        currentIndexSpan.text(currentImageIndex + 1);
        popup.addClass('active');
        $('body').css('overflow', 'hidden'); // Prevent body scroll
    }

    function closePopup() {
        popup.removeClass('active');
        $('body').css('overflow', ''); // Restore body scroll
    }

    function showPrevImage() {
        currentImageIndex = (currentImageIndex - 1 + galleryImages.length) % galleryImages.length;
        popupImage.attr('src', galleryImages[currentImageIndex]);
        currentIndexSpan.text(currentImageIndex + 1);
    }

    function showNextImage() {
        currentImageIndex = (currentImageIndex + 1) % galleryImages.length;
        popupImage.attr('src', galleryImages[currentImageIndex]);
        currentIndexSpan.text(currentImageIndex + 1);
    }

    // Touch swipe support for mobile
    let touchStartX = 0;
    let touchEndX = 0;

    popup.on('touchstart', function (e) {
        touchStartX = e.changedTouches[0].screenX;
    });

    popup.on('touchend', function (e) {
        touchEndX = e.changedTouches[0].screenX;
        handleSwipe();
    });

    function handleSwipe() {
        const swipeThreshold = 50;
        if (touchEndX < touchStartX - swipeThreshold) {
            // Swipe left - show next image
            showNextImage();
        }
        if (touchEndX > touchStartX + swipeThreshold) {
            // Swipe right - show previous image
            showPrevImage();
        }
    }
});
