var customer = {

    init: function() {
        customer.initEvent();
    },

    initEvent: function()
    {
        $('#customer_billing_is_delivery').change(customer.toggleDeliveryForm);
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
    }
};

$(function() {
    customer.init();
})