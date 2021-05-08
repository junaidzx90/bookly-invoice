jQuery(function ($) {
    $('#select_user').on("change", function () {
        let user_id = $(this).val();
        if($(this).val() !== "")
            $.ajax({
                type: "post",
                url: admin_ajax_action.ajaxurl,
                data: {
                    action: "get_user_appoint_information",
                    user_id: user_id
                },
                dataType: "json",
                success: function (response) {
                    if (response) {
                        let data = JSON.stringify(response);
                        data = data.replace(/\\/g, '');
                        $('#payments_found').html(data)
                    }
                }
            });
    });

    $(document).on("click", '#view_invoice', function () {
        let customer_id = $(this).attr('customer_id');
        $.ajax({
            type: "get",
            url: admin_ajax_action.ajaxurl,
            data: {
                action: "get_invoice_data",
                payment_id: customer_id
            },
            dataType: "json",
            success: function (response) {
                
            }
        });
        $(this).prop('id', 'close_invoice').text('CLOSE');
        $(this).parent().parent().after('<tr class="child_elm"><td>Hello</td></tr>');
    });

    $(document).on("click", '#close_invoice', function () {
        $(this).prop('id', 'view_invoice').text('VIEW');
        $(this).parent().parent().next('.child_elm').remove();
    });
});