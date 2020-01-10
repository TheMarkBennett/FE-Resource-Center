$(function() {

     $('.fe-anchor').on('click', function(e) {
        // prevent default anchor click behavior
        e.preventDefault();

        var hash = this.hash;

        if ($(hash).length) {
            $('html, body').animate({
                scrollTop: $(hash).offset().top
            }, 500, function() {
                // Do something fun if you want!
            });
        }
    
       console.log('working');
    });
});