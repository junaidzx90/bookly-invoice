jQuery(function ($) {
    $('#select_user').select2();
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

    // Save admin info in invoice
    $(document).on("click", '.editmood', function () {
        $(this).prop('class', 'savemood').text("Save");
        $('.edit_inp').show();
    });
    $(document).on("click", '.savemood', function () {
        let btn = $(this);
        let ceoinp = $('.ceoinp').val();
        let phoneinp = $('.phoneinp').val();
        let emlinp = $('.emlinp').val();
        $.ajax({
            type: "post",
            url: admin_ajax_action.ajaxurl,
            data: {
                action: "save_admin_invoce_info",
                ceoinp: ceoinp,
                phoneinp: phoneinp,
                emlinp:emlinp
            },
            beforeSend: ()=>{
                btn.text("Saving");
            },
            success: function (response) {
                btn.prop('class', 'editmood').text("Edit");
                $('.edit_inp').hide();
            }
        });
    });

    $(document).on('keyup', '.ceoinp', function () {
        $('.ceotext').text($(this).val())
    });
    $(document).on('keyup', '.phoneinp', function () {
        $('.phonetext').text($(this).val())
    });
    $(document).on('keyup', '.emlinp', function () {
        $('.mailtext').text($(this).val())
    });
});