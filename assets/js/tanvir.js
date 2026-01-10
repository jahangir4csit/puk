jQuery(document).ready(function ($) {

    // $(".filter_input").on("change", function () {
        $("body").delegate(".filter_input", "change", function (event) {

        // find parent class like filter_category_item_4
        let parentClass = $(this).closest("[class*='filter_category_item_']").attr("class");

        // extract number using regex
        let match = parentClass.match(/filter_category_item_(\d+)/);
        let categoryNumber = match ? match[1] : null;

        // alert("Clicked filter category number: " + categoryNumber); 
        // alert(".show_product_table_"+categoryNumber) ;  

        let selectedFilters = [];
        // collect all checked checkbox values
        $(".filter_input:checked").each(function () {
            selectedFilters.push($(this).attr("id"));
        });
        
        //  alert(selectedFilters) ; 
        $.ajax({
            url: ajax_object.ajax_url, 
            type: "POST",
            data: {
                action: "filter_products_by_metadata",
                filters: selectedFilters,
            }, 
            beforeSend: function () {
                $(".show_product_table_"+categoryNumber).html("<p>Loading...</p>");
            },
            success: function (response) {
                $(".show_product_table_"+categoryNumber).html(response);
            }

        });

    });

});


 var swiper = new Swiper(".mySwiper_about_us", {
      slidesPerView: 1,
      spaceBetween: 30,
    // CORRECTED: Use 'navigation' instead of nextButton/prevButton
        navigation: {
            nextEl: '.abt_us_2 .swiper-button-next',
            prevEl: '.abt_us_2 .swiper-button-prev',
        },
    
      pagination: {
        el: ".swiper-pagination",
        clickable: true,
      
      },
    });


// lightbox_puk functionality
    const lightbox_puk = document.getElementById('lightbox_puk');
    const lightbox_pukImage = document.getElementById('lightbox_puk-image');
    const lightbox_pukClose = document.getElementById('lightbox_puk-close');
    const lightbox_pukPrev = document.getElementById('lightbox_puk-prev');
    const lightbox_pukNext = document.getElementById('lightbox_puk-next');
    const lightbox_pukCounter = document.getElementById('lightbox_puk-counter');
    const lightbox_pukLoader = document.getElementById('lightbox_puk-loader');
    
    const galleryImages = document.querySelectorAll('.prjct_pg_dtls_1_right_bottom img');
    let currentImageIndex = 0;
    
    // Convert NodeList to Array for easier manipulation
    const imageArray = Array.from(galleryImages);
    
    // Open lightbox_puk when clicking on an image
    galleryImages.forEach((img, index) => {
        img.addEventListener('click', () => {
            openlightbox_puk(index);
        });
    });
    
    function openlightbox_puk(index) {
        currentImageIndex = index;
        lightbox_puk.classList.add('active');
        document.body.style.overflow = 'hidden'; // Prevent scrolling
        updatelightbox_pukImage();
    }
    
    function closelightbox_puk() {
        lightbox_puk.classList.remove('active');
        document.body.style.overflow = ''; // Restore scrolling
    }
    
    function updatelightbox_pukImage() {
        const img = imageArray[currentImageIndex];
        
        // Show loader
        lightbox_pukLoader.style.display = 'block';
        lightbox_pukImage.style.display = 'none';
        
        // Update image
        lightbox_pukImage.src = img.src;
        lightbox_pukImage.alt = img.alt;
        
        // Hide loader when image is loaded
        lightbox_pukImage.onload = () => {
            lightbox_pukLoader.style.display = 'none';
            lightbox_pukImage.style.display = 'block';
        };
        
        // Update counter
        lightbox_pukCounter.textContent = `${currentImageIndex + 1} / ${imageArray.length}`;
    }
    
    function showPrevImage() {
        currentImageIndex = (currentImageIndex - 1 + imageArray.length) % imageArray.length;
        updatelightbox_pukImage();
    }
    
    function showNextImage() {
        currentImageIndex = (currentImageIndex + 1) % imageArray.length;
        updatelightbox_pukImage();
    }
    
    // Event Listeners
    lightbox_pukClose.addEventListener('click', closelightbox_puk);
    lightbox_pukPrev.addEventListener('click', showPrevImage);
    lightbox_pukNext.addEventListener('click', showNextImage);
    
    // Close lightbox_puk when clicking outside the image
    lightbox_puk.addEventListener('click', (e) => {
        if (e.target === lightbox_puk) {
            closelightbox_puk();
        }
    });
    
    // Keyboard navigation
    document.addEventListener('keydown', (e) => {
        if (!lightbox_puk.classList.contains('active')) return;
        
        if (e.key === 'Escape') {
            closelightbox_puk();
        } else if (e.key === 'ArrowLeft') {
            showPrevImage();
        } else if (e.key === 'ArrowRight') {
            showNextImage();
        }
    });
    
    // Touch/Swipe support for mobile
    let touchStartX = 0;
    let touchEndX = 0;
    
    lightbox_puk.addEventListener('touchstart', (e) => {
        touchStartX = e.changedTouches[0].screenX;
    });
    
    lightbox_puk.addEventListener('touchend', (e) => {
        touchEndX = e.changedTouches[0].screenX;
        handleSwipe();
    });
    
    function handleSwipe() {
        const swipeThreshold = 50;
        const diff = touchStartX - touchEndX;
        
        if (Math.abs(diff) > swipeThreshold) {
            if (diff > 0) {
                showNextImage(); // Swipe left
            } else {
                showPrevImage(); // Swipe right
            }
        }
    }