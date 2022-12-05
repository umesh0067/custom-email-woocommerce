jQuery(document).ready(function($) {

    $(".email-trigger").on("click", function(e) {
  
      e.preventDefault();
  
      $.ajax({
          url: um_ajax_email.wc_ajax_url.toString(),
          type: 'POST',
          data: {
            'whatever': 1234
          },
        })
        .done(function(data) {
          if (console && console.log) {
            console.log(data);
          }
        });
    });
  
  });