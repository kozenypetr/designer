var customer = {

    init: function() {
        customer.initValidation();
        customer.initEvent();
    },

    initEvent: function()
    {
        $('#customer_billing_is_delivery').change(customer.toggleDeliveryForm);
        $('#customer_billing_is_delivery').change();
        $('#customer_billing_is_create_account').change(customer.togglePasswordForm);
        $('#customer_billing_is_create_account').change();
        $('#login').submit(customer.login);
        $('.login-box .show-form').click(customer.showLoginForm);


    },

    initValidation: function()
    {
        $('#login').validate();
    },

    toggleDeliveryForm: function(event)
    {
        if ($(this).is(':checked'))
        {
            $('.delivery-form').show();

        }
        else
        {
            $('.delivery-form').hide();
        }
    },

    togglePasswordForm: function(event)
    {
        if ($(this).is(':checked'))
        {
            $('.password-form').show();

        }
        else
        {
            $('.password-form').hide();
        }
    },

    showLoginForm: function(event)
    {
        event.preventDefault();

        $('.login-box .login-box-form').toggle('slow');

    },

    login: function(event)
    {
        if (!$(this).valid())
        {
            return false;
        }

        event.preventDefault();

        var url = $(this).attr('action');

        var data = {
            _username: $("#username").val(),
            _password: $("#password").val()
        }

        $.ajax({
            type: 'POST',
            // dataType: 'json',
            // contentType: "application/json",
            url: url,
            data: data,// JSON.stringify(data),
            async: false,
            success: function (data) {
                if (data.redirect)
                {
                    window.location.href = data.redirect;
                }
                else
                {
                    location.reload();
                }
            },
            statusCode: {
                401: function() {
                    $('.login-error').show();
                }
            }
        });
    }
};

$(function() {
    customer.init();
})