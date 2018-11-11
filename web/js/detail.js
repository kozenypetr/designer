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
                defaultType: "image",
                hash: false
            }
        );

        $('#product-detail-form').validate();
    }
};

$(function() {
    detail.init();
});