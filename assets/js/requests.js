(function ($) {
    'use strict';

    $('.sort-apply').on('click',function () {
        $.ajax({
            type: "post",
            url: bb_ul_obj.ajaxurl,
            data: {
                'action': 'count_online_visitors',
                'nonce': online_visitors_obj.ajax_nonce
            },
            success: function (data) {
                console.log(data);
                if (typeof data.online_post_visitors !== 'undefined') {
                    // backend says: we are in a single post
                    //$('.lv-visitors .lv-visitors-count').html(data.online_post_visitors);
                }
            },
            error: function (errorThrown) {
                console.log(errorThrown);
            }
        });
    });

})(jQuery);