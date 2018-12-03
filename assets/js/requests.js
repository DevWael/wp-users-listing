(function ($) {
    'use strict';
    /**
     * Pagination ajax click event inspired by this article:
     * https://aiocollective.com/blog/click-doesn-t-work-after-ajax-load-jquery/
     */
    $('.user-listing').on('click', '.sort-apply', function (e) {
        e.preventDefault();
        var data_request = {
            'action': 'user_listing',
            'offset': $(this).data('offset'),
            'role_filer': $('#bb_role_names_filter').val(),
            'order_by': $('#bb_sorting_by').val(),
            'order_method': $('#bb_sorting_method').val(),
            'nonce': bb_ul_obj.ajax_nonce
        };
        if (!$(this).hasClass('blue-button')) {
            $('.blue-button').attr('data-offset', $(this).data('offset'));
        }
        $.ajax({
            type: "post",
            url: bb_ul_obj.ajaxurl,
            data: data_request,
            success: function (data) {
                //console.log(data);
                if(typeof data.users_data !== "undefined"){
                    $(".tablenav.users-pagination .tablenav-pages").remove();
                    $(".user-data").remove();
                    $(".table-template tbody").append(data.users_data);
                    if(typeof data.pagination_data !== "undefined") {
                        $(".tablenav.users-pagination").append(data.pagination_data);
                    }
                }
            },
            error: function (errorThrown) {
                //console.log(errorThrown);
            }
        });
    });
    //reset the offset if the role filter changed
    $('#bb_role_names_filter').on('change', function () {
        $('.blue-button').attr('data-offset', 0);
    });
})(jQuery);