var customer = {

    init: function() {
        customer.initEvent();
    },

    initEvent: function()
    {
        $('#customer_billing_is_delivery').change(customer.toggleDeliveryForm);
        $('#customer_billing_is_delivery').change();
        $('#customer_billing_is_create_account').change(customer.togglePasswordForm);
        $('#customer_billing_is_create_account').change();
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
    }
};

$(function() {
    customer.init();
})