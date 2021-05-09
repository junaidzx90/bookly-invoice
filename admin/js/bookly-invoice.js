jQuery(function ($) {
    $('#select_user').on("change", function () {
        let user_id = $(this).val();
        if ($(this).val() !== "")
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
                        data = data.slice(1, -1);
                        $('.payments').html(data)
                    }
                }
            });
        else
            $('#wrapper').remove();
            $('button').remove();
    });
});