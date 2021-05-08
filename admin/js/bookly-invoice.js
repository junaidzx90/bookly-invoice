jQuery(function ($) {
    $('#select_user').on("change", function () {
        let user_id = $(this).val();
        if($(this).val() !== "")
            $.ajax({
                type: "get",
                url: admin_ajax_action.ajaxurl,
                data: {
                    action: "get_invoice_data",
                    user_id: user_id
                },
                dataType: "json",
                success: function (response) {
                    if (response) {
                        let data = JSON.stringify(response);
                        data = data.replace(/\\/g, '');
                        data = data.slice(1,-1);
                        $('.payments').html(data)
                        // mthis.prop('id', 'close_invoice').text('CLOSE');
                        // mthis.parent().parent().after('<tr class="child_elm"><td class="full">'+data+'</td></tr>');
                    }
                }
            });
    });

    // $(document).on("click", '#view_invoice', function () {
    //     let mthis = $(this);
    //     let payment_id = $(this).attr('payment_id');
    //     $.ajax({
    //         type: "get",
    //         url: admin_ajax_action.ajaxurl,
    //         data: {
    //             action: "get_invoice_data",
    //             payment_id: payment_id
    //         },
    //         dataType: "json",
    //         success: function (response) {
    //             if (response) {
    //                 let data = JSON.stringify(response);
    //                 data = data.replace(/\\/g, '');
    //                 $('body').append('<div class="popup"> <div class="poupcontents">'+
    //                 data+
    //                 '</div></div>')
    //                 // mthis.prop('id', 'close_invoice').text('CLOSE');
    //                 // mthis.parent().parent().after('<tr class="child_elm"><td class="full">'+data+'</td></tr>');
    //             }
    //         }
    //     });
    // });

    // $(document).on("click", '#close_invoice', function () {
    //     $(this).prop('id', 'view_invoice').text('VIEW');
    //     $('.popup').remove();
    // });

    $(document).on("click",'.popup', function (e) {
        if ($(e.target).hasClass('poupcontents')) {
            $('.popup').remove();
        }
    });
});