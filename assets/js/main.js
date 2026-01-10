(function ($) {
    $(document).ready(function () {

        //         $('.hm_menu').slicknav({
        //             label: '',
        //             appendTo: '.mobile_menu',
        //             easingOpen: 'swing',
        //                allowParentLinks: "true"
        //         });

        //          $(window).scroll(function () {
        //           var scroll_top = $(window).scrollTop();
        //           if (scroll_top > 80) {
        //             $(".front_page_menu").addClass('fpage_normal_menu');
        //             $(".slicknav_btn").addClass('nav_btn_scroll');
        //           } else {
        //             $(".front_page_menu").removeClass('fpage_normal_menu');
        //             $(".slicknav_btn").removeClass('nav_btn_scroll');
        //           }
        //         })



        //     $('.crizaf_img_popup').magnificPopup({
        //       type: 'image'
        //     });


        // var grid = $('.grid_items').isotope();

        // grid.imagesLoaded().progress( function() {

        //     grid.isotope('layout');

        //     $('.menu_box').on('click', 'li', function () {
        //         var filterValue = $(this).attr('data-filter');
        //         grid.isotope({ filter: filterValue });
        //     });


        //   });



        // nel mondo country search 
        // $("#nl_mnd_country_search").on('keyup', function () {
        //     var input_value = $("#nl_mnd_country_search").val();
        //     var input_value_length = input_value.length;
        //     if (input_value_length > 0) {
        //         $.ajax({
        //             url: url,
        //             data: {
        //                 action: 'nel_mondo_country_search',
        //                 input_value: input_value,
        //             },
        //             type: 'post',
        //             success: function (data) {
        //                 $(".crzf_country_list").html(data);
        //             },
        //         });
        //     } else {
        //         $.ajax({
        //             url: url,
        //             data: {
        //                 action: 'existing_nel_mondo_country_search',
        //                 input_value: input_value,
        //             },
        //             type: 'post',
        //             success: function (data) {
        //                 $(".crzf_country_list").html(data);
        //             },
        //         });
        //     }
        // });




        // $("#category_collection_form").on('submit', function (e) {
        //     e.preventDefault();
        //     let spinner = `
        //         <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
        //         Loading...`;
        //     $('#category_collection_form .error').html('');
        //     $('#c_save_collection').html(spinner);
        //     $('#c_save_collection').prop('disabled', true);
        //     var get_site_url = $(".watch_site_url").attr('setup_site_url');
        //     var url = action_url_ajax.ajax_url;
        //     var form = new FormData($('#category_collection_form')[0]);
        //     form.append("action", 'wb_user_new_category_collection_from_submit');
        //     jQuery.ajax({
        //         type: 'POST',
        //         url: url,
        //         data: form,
        //         processData: false,
        //         contentType: false,
        //         dataType: 'JSON',
        //         success: function (data, textStatus, XMLHttpRequest) {
        //             console.log(data);
        //             if (data.error == true) {
        //                 if (data.check == true) {
        //                     $.each(data.message, function (key, value) {
        //                         $(".error_" + value[0]).html(value[1]);
        //                     });
        //                 } else {
        //                     alert(data.message);
        //                 }
        //             } else {
        //                 $('#under_review_msg').addClass('review_msg_display');
        //                 document.getElementById("category_collection_form").reset();
        //                 const term_id = data.term_id;
        //                 var origin_site_url = $(location).attr('origin');
        //                 window.location.href = origin_site_url + `/lista-di-raccolta/?cid=${term_id}`;
        //             }
        //             $('#c_save_collection').html(`<span>manda in approvazione</span>
        //                     <span>
        //                         <img src="${get_site_url}/assets/images/icons/Freccia.svg" alt="Freccia">
        //                         <img src="${get_site_url}/assets/images/icons/Freccia.svg" alt="Freccia">
        //                     </span>`);
        //             $('#c_save_collection').prop('disabled', false);
        //         },
        //         error: function (MLHttpRequest, textStatus, errorThrown) {
        //             alert(errorThrown);
        //         }

        //     });
        // });



        // $("body").delegate('.term_filter_class_input', 'click', function (e) {

        //     const spinner = `<div class="text-center" style="height:150px">
        //     <div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>`;
        //     $('.display_filter_data').html(spinner);

        //     const input_id = $(this).attr('id');
        //     if ($(`#${input_id}`).prop("checked") == true) {
        //         $(`#${input_id}`).prop("checked", true);
        //     } else {
        //         $(`#${input_id}`).prop("checked", false);
        //     }

        //     let value = $(this).val();
        //     if (value == 'ASC') {
        //         $(`#za`).prop("checked", false);
        //     }
        //     if (value == 'DESC') {
        //         $(`#az`).prop("checked", false);
        //     }
        //     var form = new FormData($('#product_filter_form')[0]);
        //     form.append("action", 'product_filter_form_action');
        //     form.append("slider_filter_items", encodeURIComponent(JSON.stringify(slider_filter_items)));
        //     jQuery.ajax({
        //         type: 'POST',
        //         url: url,
        //         data: form,
        //         processData: false,
        //         contentType: false,
        //         dataType: 'JSON',
        //         success: function (data) {
        //             if (data.error == true) {
        //                 alert(data.message);
        //             } else {
        //                 console.log(data.results);
        //                 $(".display_filter_data").html(data.results.post_tems);
        //                 $("#accordionPanelsStayOpenExample").html(data.results.left_side_html);
        //                 range_slider(data.results.is_lc_passaggio_term_id, data.results.is_ing_ingombro_term_id);
        //             }
        //         },
        //     });
        // });










    });
})(jQuery)


