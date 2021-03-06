

$(function() {

   $('.carousel').carousel();

   $.CookiesMessage({
       messageText: "Internetový obchod gowood.cz ukládá soubory cookies, které pomáhají k jeho správnému fungování. Využíváním našich služeb s jejich používáním souhlasíte.",
       acceptText: "Rozumím",
       infoText: "Více informací",
       infoUrl: "/pravidla-pouzivani-cookies",
   });

   $.fancybox.defaults.hash = false;

   $('.btn-menu-toggle').click(function(){
       $('#main-menu').toggle();
   });

    $('.btn-user-toggle').click(function(){
        $('#user-menu-mobile').toggle();
    });

    $('#categories-mobile-toggle').click(function(){
        $('#categories-boxes').toggle();
    });

   if ($('.fancybox').length) {

       $('.fancybox').fancybox(
           {
               buttons: [
                   "thumbs",
                   "close"
               ],
               defaultType: "image",
               hash: false
           }
       );
   }
    /*var modal = $('#modal').iziModal({
        headerColor: '#716960'
    });
    // then you can use:
    modal.iziModal('open');*/

    $('.cart-item-image').fancybox(
        {
            buttons: [
                "thumbs",
                "close"
            ],
            defaultType: "image",
            hash: false
        }
    );


   if ($('#contact-form').length)
   {
      $('#contact-form').validate();

      $('#contact-form').submit(function(e){
          e.preventDefault();

          if (!$('#contact-form').valid())
          {
              return false;
          }

          $.ajax({
              type: 'POST',
              dataType: 'json',
              // contentType: "application/json",
              url: $(this).attr('action'),
              data: $(this).serialize(),
              async: false,
              success: function (data) {
                  if (data.status == 'OK')
                  {
                      $('#contact-form input[type=text]').val('');
                      $('#contact-form input[type=email]').val('');
                      $('#contact-form textarea').val('');
                      $('#contact-form-message').show();
                  }
                  else
                  {
                      alert('Dotaz nebylo možné odeslat.');
                  }
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