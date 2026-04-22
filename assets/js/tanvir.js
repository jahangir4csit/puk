jQuery(document).ready(function ($) {


    $('#main-menu .not-clickable > a').on('click', function (e) {
        e.preventDefault();
    });

});


// About Timeline — two synchronized Swipers
(function () {
    var topEl = document.querySelector('.mySwiper_timeline_top');
    var navEl = document.querySelector('.mySwiper_timeline_nav');
    if (!topEl || !navEl) return;

    var swiperTimelineNav = new Swiper('.mySwiper_timeline_nav', {
        slidesPerView: 1,
        spaceBetween: 0,
        speed: 600,
        allowTouchMove: false,
        
    });

    var swiperTimelineTop = new Swiper('.mySwiper_timeline_top', {
        slidesPerView: 1,
        spaceBetween: 0,
        speed: 600,
        autoHeight: false,
        allowTouchMove: true,
        on: {
            slideChange: function () {
                swiperTimelineNav.slideTo(this.activeIndex);
            }
        }
    });

    // Clicking prev nav item navigates top slider back
    document.querySelectorAll('.abt_us_2_nav_prev').forEach(function (el) {
        el.addEventListener('click', function () {
            if (!el.classList.contains('is-hidden')) {
                swiperTimelineTop.slidePrev();
            }
        });
    });

    // Clicking next nav item navigates top slider forward
    document.querySelectorAll('.abt_us_2_nav_next').forEach(function (el) {
        el.addEventListener('click', function () {
            if (!el.classList.contains('is-hidden')) {
                swiperTimelineTop.slideNext();
            }
        });
    });
})();


// =============================
// Reusable Lightbox
// =============================

const lightbox = document.getElementById('lightbox_puk');
const lightbox_Image = document.getElementById('lightbox_puk-image');
const lightbox_Close = document.getElementById('lightbox_puk-close');
const lightbox_Prev = document.getElementById('lightbox_puk-prev');
const lightbox_Next = document.getElementById('lightbox_puk-next');
const lightbox_Counter = document.getElementById('lightbox_puk-counter');
const lightbox_Loader = document.getElementById('lightbox_puk-loader');
const lightbox_VideoWrap = document.getElementById('lightbox_puk-video-wrap');
const lightbox_Video = document.getElementById('lightbox_puk-video');
const lightbox_Iframe = document.getElementById('lightbox_puk-iframe');

let itemsArray = [];
let currentIndex = 0;

// Event delegation — works for any .lightbox_img, even dynamically added
document.addEventListener('click', (e) => {
    const wrapper = e.target.closest('.lightbox_img');
    if (!wrapper) return;

    itemsArray = Array.from(document.querySelectorAll('.lightbox_img'));
    const index = itemsArray.indexOf(wrapper);
    if (index !== -1) openLightbox(index);
});

function openLightbox(index) {
    currentIndex = index;
    lightbox.classList.add('active');
    document.body.style.overflow = 'hidden';
    updateLightboxContent();
}

function closeLightbox() {
    stopVideo();
    lightbox.classList.remove('active');
    document.body.style.overflow = '';
}

function stopVideo() {
    if (lightbox_Video) {
        lightbox_Video.pause();
        lightbox_Video.src = '';
    }
    if (lightbox_Iframe) {
        lightbox_Iframe.src = '';
        lightbox_Iframe.style.display = 'none';
    }
    if (lightbox_VideoWrap) {
        lightbox_VideoWrap.style.display = 'none';
    }
}

function updateLightboxContent() {
    const wrapper = itemsArray[currentIndex];
    const videoType = wrapper.dataset.videoType;
    const videoSrc = wrapper.dataset.videoSrc;

    lightbox_Counter.textContent = `${currentIndex + 1} / ${itemsArray.length}`;

    if (videoSrc) {
        // Video item
        stopVideo();
        lightbox_Loader.style.display = 'none';
        lightbox_Image.style.display = 'none';
        lightbox_VideoWrap.style.display = 'flex';

        if (videoType === 'video_upload') {
            lightbox_Video.style.display = 'block';
            lightbox_Iframe.style.display = 'none';
            lightbox_Video.src = videoSrc;
        } else {
            // YouTube or Vimeo embed
            lightbox_Iframe.style.display = 'block';
            lightbox_Video.style.display = 'none';
            lightbox_Iframe.src = videoSrc;
        }
    } else {
        // Image item
        stopVideo();
        const img = wrapper.querySelector('img');
        lightbox_Loader.style.display = 'block';
        lightbox_Image.style.display = 'none';
        lightbox_Image.src = img.src;
        lightbox_Image.alt = img.alt;
        lightbox_Image.onload = () => {
            lightbox_Loader.style.display = 'none';
            lightbox_Image.style.display = 'block';
        };
    }
}

function showPrevItem() {
    currentIndex = (currentIndex - 1 + itemsArray.length) % itemsArray.length;
    updateLightboxContent();
}

function showNextItem() {
    currentIndex = (currentIndex + 1) % itemsArray.length;
    updateLightboxContent();
}

// Controls
lightbox_Close.addEventListener('click', closeLightbox);
lightbox_Prev.addEventListener('click', showPrevItem);
lightbox_Next.addEventListener('click', showNextItem);

// Click outside to close
lightbox.addEventListener('click', (e) => {
    if (e.target === lightbox) closeLightbox();
});

// Keyboard navigation
document.addEventListener('keydown', (e) => {
    if (!lightbox.classList.contains('active')) return;
    if (e.key === 'Escape') closeLightbox();
    if (e.key === 'ArrowLeft') showPrevItem();
    if (e.key === 'ArrowRight') showNextItem();
});

// Touch/Swipe support for mobile
let touchStartX = 0;
let touchEndX = 0;

lightbox.addEventListener('touchstart', (e) => {
    touchStartX = e.changedTouches[0].screenX;
});

lightbox.addEventListener('touchend', (e) => {
    touchEndX = e.changedTouches[0].screenX;
    handleSwipe();
});

function handleSwipe() {
    const swipeThreshold = 50;
    const diff = touchStartX - touchEndX;
    if (Math.abs(diff) > swipeThreshold) {
        if (diff > 0) showNextItem();
        else showPrevItem();
    }
}