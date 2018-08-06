var productImages = {
    /*myProperty: "hello",


    myMethod: function() {
        console.log( myFeature.myProperty );
    },*/

    selector: "#product-images",

    init: function() {
        productImages.initRotate();
        productImages.initDelete();
        productImages.initSortable();
    },

    initRotate: function()
    {
        $(productImages.selector + ' .rotate').on('click', function(e)
        {
            e.preventDefault();
            var parent = $(this).parent();
            var id  = parent.data('id');
            var url = $(this).attr('href');

            parent.find('.loader').show();

            $.ajax({
                dataType: "json",
                url: url,
                success: function(data)
                {
                    $('#image-' + id + ' img').attr('src', data.url);
                    parent.find('.loader').hide();
                }
            });
        });
    },

    initDelete: function()
    {
        $(productImages.selector + ' .delete').on('click', function(e)
        {
            e.preventDefault();
            var parent = $(this).parent();
            var id  = parent.data('id');
            var url = $(this).attr('href');

            parent.find('.loader').show();

            $.ajax({
                dataType: "json",
                url: url,
                success: function(data)
                {
                    $('#image-' + id).remove();
                }
            });
        });
    },

    initSortable: function()
    {
        $(productImages.selector).sortable({
            items: ".item",
            cursor: "move",
            placeholder: 'emptyitem',
            handle: ".drag",
            update: function(event, ui) {

                var itemOrder = $(productImages.selector).sortable("toArray");

                var ajaxResult = true;

                $.ajax({
                    dataType: "json",
                    data: {sort: itemOrder },
                    async: false,
                    url: Routing.generate('product_admin_sort_image'),
                    success: function(data)
                    {

                        if (data.status != 'OK')
                        {
                            ajaxResult = false;
                        }
                    }
                });

                if (!ajaxResult)
                {
                    alert('Pri razeni nastala chyba');
                    event.preventDefault();
                }


            }
        });
    },

    updateList: function(url)
    {
        $('#images').load(url, function(){
            productImages.initRotate();
            productImages.initDelete();
            productImages.initSortable();
        });

    }

};


$( document ).ready(function() {
    productImages.init();
});