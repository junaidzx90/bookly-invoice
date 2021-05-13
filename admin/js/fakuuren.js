jQuery(function ($) {
    $('#select_user').select2();
    $('#select_user').on("change", function () {
        let user_id = $(this).val();
        if ($(this).val() !== "")
            $.ajax({
                type: "get",
                url: admin_ajax_action.ajaxurl,
                data: {
                    action: "get_fakuuren_data",
                    user_id: user_id
                },
                dataType: "json",
                beforeSend: ()=>{
                    $('.loading').show();
                },
                success: function (response) {
                    if (response) {
                        $('.loading').hide();
                        let data = JSON.stringify(response);
                        data = data.replace(/\\/g, '');
                        data = data.slice(1, -1);
                        $('.payments').html(data);
                    }
                }
            });
        else
            $('.payments').html('<h1 class="selectuser">Please select a customer</h1>');
            $('#wrapper').remove();
            $('button').remove();
    });

    // Save admin info in fakuuren
    $(document).on("click", '.editmood', function () {
        $(this).prop('class', 'savemood').text("Save");
        $('.edit_inp').css('display','flex');
    });

    $(document).on("click", '.savemood', function () {
        let btn = $(this);
        let company = $('#company').val();
        let ceoinp = $('#ceoinp').val();
        let vatin = $('#vatin').val();
        let address = $('#address').val();
        let sign = $('#sign').val();
        let phoneinp = $('#phoneinp').val();
        let emlinp = $('#emlinp').val();
        $.ajax({
            type: "post",
            url: admin_ajax_action.ajaxurl,
            data: {
                action: "save_admin_invoce_info",
                company: company,
                vatin: vatin,
                address: address,
                sign: sign,
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

    $(document).on('keyup', '#company', function () {
        $('.companytext').text($(this).val())
    });
    $(document).on('keyup', '#ceoinp', function () {
        $('.ceotext').text($(this).val())
    });
    $(document).on('keyup', '#phoneinp', function () {
        $('.phonetext').text($(this).val())
    });
    $(document).on('keyup', '#emlinp', function () {
        $('.mailtext').text($(this).val())
    });
    $(document).on('keyup', '#vatin', function () {
        $('.vatintext').text($(this).val())
    });
    $(document).on('keyup', '#address', function () {
        $('.addresstext').text($(this).val())
    });
    $(document).on('keyup', '#sign', function () {
        $('.signeture_text').children('strong i').text($(this).val())
    });
});