

(function ($) {
    $(document).ready(function () {

         // var url = action_url_ajax.ajax_url;
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






 // nel mondo country search 
    // $("#nl_mnd_country_search").on('keyup', function () {
    //   var input_value = $("#nl_mnd_country_search").val();
    //   var input_value_length = input_value.length;
    //   if (input_value_length > 0) {
    //     $.ajax({
    //       url: url,
    //       data: {
    //         action: 'nel_mondo_country_search',
    //         input_value: input_value,
    //       },
    //       dataType: "json",
    //       type: 'post',
    //       success: function (data) {
    //         $(".crzf_country_list").html(data);
    //       },
    //     });
    //   } else {
    //     $.ajax({
    //       url: url,
    //       data: {
    //         action: 'existing_nel_mondo_country_search',
    //         input_value: input_value,
    //       },
    //       dataType: "json",
    //       type: 'post',
    //       success: function (data) {
    //         $(".crzf_country_list").html(data);
    //       },
    //     });
    //   }
    // });














        


        


    });

})(jQuery)
