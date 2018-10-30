$(function() {
   $('.btn-menu-toggle').click(function(){
       $('.menu').toggle();
   });


    $('.fancybox').fancybox(
        {
            buttons: [
                "thumbs",
                "close"
            ],
            defaultType: "image"
        }
    );

});