(function ($) {
  $(document).ready(function () {
    // code goes here

    $('[data-background]').each(function () {
      $(this).css({
        'background-image': 'url(' + $(this).attr('data-background') + ')',
        'background-size': 'cover',
        'background-position': 'center center',
        'background-repeat': 'no-repeat',
      });
    });

    // magnific popup start

    // $('.pf-gallary-main .pf-grallary-grid .single-grid').magnificPopup({
    //   delegate: 'a',
    //   type: 'image',
    //   tLoading: 'Loading image #%curr%...',
    //   mainClass: 'mfp-img-mobile',
    //   gallery: {
    //     enabled: true,
    //     navigateByImgClick: true,
    //     preload: [0, 1], // Will preload 0 - before current, and 1 after the current image
    //   },
    //   image: {
    //     tError: '<a href="%url%">The image #%curr%</a> could not be loaded.',
    //     titleSrc: function (item) {
    //       return item.el.attr('title') + ' ';
    //     },
    //   },
    // });

    // magnific popup ends

    // pf accordion start

    $('.pf-accordion-main .accordion-header').click(function () {
      let $this = $(this);
      let content = $this.next('.accordion-content');

      if ($this.hasClass('active')) {
        $this.removeClass('active');
        content.slideUp(300);
        $this.find('.accordion-text-closed').show();
        $this.find('.accordion-text-open').hide();
      } else {
        // Close other accordions
        $('.pf-accordion-main .accordion-header').removeClass('active');
        $('.pf-accordion-main .accordion-content').slideUp(300);
        $('.pf-accordion-main .accordion-header .accordion-text-closed').show();
        $('.pf-accordion-main .accordion-header .accordion-text-open').hide();

        // Open current accordion
        $this.addClass('active');
        content.slideDown(300);
        $this.find('.accordion-text-closed').hide();
        $this.find('.accordion-text-open').show();
      }
    });

    // pf accordion ends

    // table innder data show hide start
    $('tr').has('.td-inner-box').hide();

    // $('.accordion-data-btn').on('click', function (e) {

    $('body').delegate('.accordion-data-btn', 'click', function (e) {
      // alert('hell') ;
      e.preventDefault();

      let $btn = $(this);
      let $currentRow = $btn.closest('tr');
      let $nextRow = $currentRow.next('tr');
      let $innerBox = $nextRow.find('.td-inner-box');

      // Close all other open rows first
      $('tr')
        .has('.td-inner-box')
        .not($nextRow)
        .each(function () {
          let $otherRow = $(this);
          let $otherInner = $otherRow.find('.td-inner-box');

          if ($otherInner.is(':visible')) {
            $otherInner.stop(true, true).slideUp(300, function () {
              $otherRow.hide();
            });
          }
        });

      // Remove active class from other buttons
      $('.accordion-data-btn').not($btn).removeClass('active');

      // Toggle current row
      if ($nextRow.is(':visible')) {
        $innerBox.stop(true, true).slideUp(300, function () {
          $nextRow.hide();
        });
        $btn.removeClass('active');
      } else {
        $nextRow.show();
        $innerBox.stop(true, true).hide().slideDown(300);
        $btn.addClass('active');
      }
    });
    // table innder data show hide ends

    var swiper = new Swiper('.project-slider', {
      slidesPerView: 'auto',
      spaceBetween: 5,
      //   loop: true,
      //   grabCursor: true,
      //   loopFillGroupWithBlank: false,
      //   centeredSlides: false,

      autoplay: {
        delay: 2500,
        disableOnInteraction: true,
      },
      //   breakpoints: {
      //     300: {
      //       slidesPerView: 1.2,
      //     },
      //     640: {
      //       slidesPerView: 1.4,
      //     },
      //     768: {
      //       slidesPerView: 1.4,
      //     },
      //     1025: {
      //       slidesPerView: 1.4,
      //     },
      //     1200: {
      //       slidesPerView: 1.9,
      //     },
      //   },
    });

    $('.light-distribution-main .inforzioni-box a').on('click', function (e) {
      e.preventDefault();
      $('.light-distribution-main .toggle-form-box').slideToggle(300);
    });

    var swiper = new Swiper('.related-product-slider', {
      slidesPerView: 5,
      spaceBetween: 10,
      navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
      },

      breakpoints: {
        0: {
          slidesPerView: 1,
          spaceBetween: 10,
        },

        480: {
          slidesPerView: 2,
          spaceBetween: 10,
        },

        768: {
          slidesPerView: 3,
          spaceBetween: 10,
        },

        992: {
          slidesPerView: 4,
          spaceBetween: 10,
        },

        1200: {
          slidesPerView: 5,
          spaceBetween: 10,
        },
      },
    });

    var swiper = new Swiper('.pf-display-slider', {
      navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
        disabledClass: 'swiper-button-disabled',
      },
      pagination: {
        el: '.swiper-pagination',
        clickable: true,
      },
      watchOverflow: true,
    });

    $(document).ready(function () {
      var currentTarget = null;

      function showImage(id) {
        var $target = $('#' + id + '-img');

        $('.origin-form-main .image-box')
          .not($target)
          .stop(true, true)
          .fadeOut(300)
          .removeClass('active');

        $target.stop(true, true).fadeIn(300).addClass('active');

        currentTarget = $target;
      }

      function hideImage() {
        if (currentTarget) {
          currentTarget.stop(true, true).fadeOut(300).removeClass('active');
          currentTarget = null;
        }
      }

      $('.ul-group-parent ul li a').mouseenter(function () {
        var id = $(this).attr('id');
        showImage(id);
      });

      $('origin-form-main .image-box').mouseenter(function () {});

      $('.ul-group-parent, .image-box').mouseleave(function () {
        var isHoveringLink = $('.ul-group-parent ul li a:hover').length > 0;
        var isHoveringImage = $('.image-box:hover').length > 0;

        if (!isHoveringLink && !isHoveringImage) {
          hideImage();
        }
      });
    });
  });
})(jQuery);
