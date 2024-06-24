$(document).ready(function (){
    let $owl = $('.owl-carousel');
    if ($($owl).length > 0) {
        $owl.on('initialized.owl.carousel', function(event) {
            $('.cnrs-dm-front-slider-wrapper').addClass('loaded');
        });
        $owl.owlCarousel({
            autoplay: true,
            items: 1,
            margin: 10,
            loop: true,
            navText: [
                '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" width="20" height="20"><path fill="currentColor" d="M9.4 233.4c-12.5 12.5-12.5 32.8 0 45.3l192 192c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L77.3 256 246.6 86.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0l-192 192z"/></svg>',
                '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" width="20" height="20"><path fill="currentColor" d="M310.6 233.4c12.5 12.5 12.5 32.8 0 45.3l-192 192c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3L242.7 256 73.4 86.6c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0l192 192z"/></svg>'
            ],
            checkVisible: false,
            responsive: {
                1200: {
                    items: $('.owl-carousel').data('count'),
                    nav: true
                },
                1000: {
                    items: 3,
                    nav: true
                },
                726: {
                    items: 2
                },
            }
        });
    }
});
