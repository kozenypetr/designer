var cart = {
    eventSelector: ".event",
    box: null,

    init: function() {
        cart.initEvent();
    },

    initEvent: function()
    {
        $(cart.eventSelector + '.keyup').unbind();
        $(cart.eventSelector + '.click').unbind();
        $(cart.eventSelector + '.change').unbind();
        $(cart.eventSelector + '.update').unbind();

        $(cart.eventSelector + '.keyup').keyup(cart.processEvent);
        $(cart.eventSelector + '.click').click(cart.processEvent);
        $(cart.eventSelector + '.change').click(cart.processEvent);
        $(cart.eventSelector + '.update').change(cart.processEvent);
        $('.shipping input').click(cart.changeShipping);
        $('.payment input').click(cart.changePayment);
    },

    changeShipping: function(event)
    {
        if($(this).attr('type') == 'radio') {
            $('.shipping .description').removeClass('show');
            $(this).parent().find('.description').addClass('show');
        }
    },

    changePayment: function(event)
    {
        $('.payment .description').removeClass('show');
        $(this).parent().find('.description').addClass('show');
    },

    processEvent: function(event)
    {
        if ($(this).attr('type') != 'radio') {
            event.preventDefault();
        }

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
            async: true,
            success: function (data) {

                if (data.redirect)
                {
                    window.location = data.redirect;
                }

                $.each(data.boxes, function( index, value ) {
                    $('#' + index).replaceWith(value);
                });


                $.each(data.values, function( index, value ) {
                    $('#' + index).text(value);
                });

                cart.initEvent();
            },
        });


    }

};

$(function() {
    cart.init();

    window.addEventListener('message', function (e) {
        if(!(e && e.data && e.data.packetaWidgetMessage)) {
            return;
        }

        $('#zasilkovna-id').click();
    });
})