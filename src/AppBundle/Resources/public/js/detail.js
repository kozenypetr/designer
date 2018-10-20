var detail = {

    init: function() {
        detail.initEvent();
    },

    initEvent: function()
    {
        $('.gallery').fancybox(
            {
                buttons: [
                    //"share",
                    // "slideShow",
                    //"fullScreen",
                    //"download",
                    "thumbs",
                    "close"
                ],
                defaultType: "image"
            }
        );

        $('#product-detail-form').validate();
    }
};

$(function() {
    detail.init();
});