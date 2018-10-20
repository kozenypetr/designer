var cart = {
    eventSelector: ".event",
    box: null,

    init: function() {
        cart.initEvent();
    },

    initEvent: function()
    {
        $(cart.eventSelector + '.keyup').keyup(cart.processEvent);
        $(cart.eventSelector + '.click').click(cart.processEvent);
        $(cart.eventSelector + '.change').click(cart.processEvent);

        $('.cart-item-image').fancybox(
            {
                buttons: [
                    "thumbs",
                    "close"
                ],
                defaultType: "image"
            }
        );
        // $('.shipping input, .payment input').click(cart.changeShippingPayment);
    },

    processEvent: function(event)
    {
        event.preventDefault();
        var url = $(this).data('handle');

        var data = null;
        if ($(this).data('value'))
        {
            var value = $(this).val();
            data = "v=" + value;

            var validation = $(this).data('validation');
            if (validation == 'number')
            {
                var regexp = /^\d+$/;
                if (!regexp.test(value))
                {
                    return false;
                }
            }
        }

        $.ajax({
            type: 'GET',
            url: url,
            data: data,
            async: false,
            success: function (data) {

                if (data.redirect)
                {
                    window.location = data.redirect;
                }

                $.each(data.boxes, function( index, value ) {
                    $('#' + index).replaceWith(value);
                });
                cart.initEvent();
            },
        });


    }

};

$(function() {
    cart.init();
})