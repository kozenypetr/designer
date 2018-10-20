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

    tinymce.init({
        selector: '.tiny',
        themes: "modern",
        menubar: false,
        language: 'cs',
        force_br_newlines : false,
        force_p_newlines : true,
        forced_root_block : '',
        convert_urls: false,
        verify_html: false,
        remove_script_host: false,
        codemirror: { indentOnInit:true, path:'/vendor/tinymce/plugins/codemirror/codemirror-4.8'},
        plugins: [
            "advlist autolink link image lists charmap print preview hr anchor pagebreak",
            "searchreplace wordcount visualblocks visualchars insertdatetime media nonbreaking spellchecker",
            "table contextmenu directionality emoticons paste textcolor code codemirror codesample table"
        ],
        height : "250",
        entity_encoding : "raw",
        toolbar1: "undo redo | bold italic underline | forecolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent",
        toolbar2: "styleselect |  image | media | link unlink | table | code codesample"
    });

});