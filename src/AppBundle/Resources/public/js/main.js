$(function() {
   $('.btn-menu-toggle').click(function(){
       $('.menu').toggle();
   });

   if ($('.fancybox').length) {

       $('.fancybox').fancybox(
           {
               buttons: [
                   "thumbs",
                   "close"
               ],
               defaultType: "image"
           }
       );
   }
    /*var modal = $('#modal').iziModal({
        headerColor: '#716960'
    });
    // then you can use:
    modal.iziModal('open');*/

   if ($('#contact-form').length)
   {
      $('#contact-form').submit(function(e){
          e.preventDefault();

          $.ajax({
              type: 'POST',
              // dataType: 'json',
              // contentType: "application/json",
              url: $(this).attr('action'),
              data: $(this).serialize(),
              async: false,
              success: function (data) {
                  alert(data);
              },
              statusCode: {
                  /*401: function() {
                      $('.login-error').show();
                  }*/
              }
          });

      });
   }

});