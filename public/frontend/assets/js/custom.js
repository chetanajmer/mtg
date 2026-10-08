$(document).ready(function(){
            $(".product_slider").owlCarousel({
                loop: true,
                margin: 10,
                autoplay:true,
                autoplayTimeout:1000,
                responsiveClass: true,
                responsive: {
                  0: {
                    items: 2,
                    nav: true,
                    autoplay:false,
                  },
                  768: {
                    items: 3,
                    nav: false
                  },
                  1280: {
                    items: 4,
                    nav: false,
                    loop: false,
                    margin:20
                  }
                }
              });
            $(".mobilecat_slider").owlCarousel({
                loop: false,
                margin: 0,
                nav: true,
                autoWidth:true,
                responsiveClass: true,
                responsive: {
                  0: {
                    items: 2,
                  },
                  768: {
                    items: 3,
                  },
                  1280: {
                    items: 3,
                  }
                }
              });
            $(".brand_slider").owlCarousel({
                loop: true,
                nav: false,
                margin: 10,
                autoplay:true,
                autoplayTimeout:1500,
                responsiveClass: true,
                responsive: {
                  0: {
                    items: 2,
                  },
                  350: {
                    items: 3,
                  },
                  768: {
                    items: 3,
                  },
                  1280: {
                    items: 6,
                    loop: false,
                    margin:0
                  }
                }
              })
            $(".relatedproduct_slider").owlCarousel({
                loop: false,
                nav: true,
                margin: 10,
                autoplay:true,
                autoplayTimeout:2000,
                responsiveClass: true,
                responsive: {
                  0: {
                    items: 2,
                  },
                  767: {
                    items: 3,
                  },
                  992: {
                    items: 4,
                  },
                  1280: {
                    items: 5,
                  },
                  1441: {
                    items: 6,
                    loop: false,
                    margin:15,
                  }
                }
              })
});
function accordionToggle() {
  $('.c-accordion .js-btn').click(function() {
    $(this).toggleClass('js-is-active');
  });
}
$(document).ready(function() {
  accordionToggle();
});
$(document).ready(function() {
    $('#vertical').lightSlider({
      gallery:true,
      item:1,
      vertical:true,
      verticalHeight:500,
      vThumbWidth:120,
      thumbItem:8,
      thumbMargin:15,
      slideMargin:0
    });  
  });
$(document).ready(function() {
      $('.minus').click(function () {
        var $input = $(this).parent().find('input');
        var count = parseInt($input.val()) - 1;
        count = count < 1 ? 1 : count;
        $input.val(count);
        $input.change();
        return false;
      });
      $('.plus').click(function () {
        var $input = $(this).parent().find('input');
        $input.val(parseInt($input.val()) + 1);
        $input.change();
        return false;
      });
    });
  $(document).ready(function(){
  $('ul.tabs li').click(function(){
    var tab_id = $(this).attr('data-tab');
    $('ul.tabs li').removeClass('current');
    $('.tab-content').removeClass('current');
    $(this).addClass('current');
    $("#"+tab_id).addClass('current');
  })
});
$(document).ready(function(){
    $( "#send_btn" ).click(function( event ) {
        event.preventDefault();
        $("html, body").animate({ scrollTop: $($(this).attr("href")).offset().top }, 750);
    });
});


  $(document).ready(function(){$(".quote").click(function(){$("body").addClass("open-it")});$(".close").click(function(){$("body").removeClass("open-it")});
  $(".quote").click(function(){$(".callsidecard").addClass("open-it")});$(".close").click(function(){$(".callsidecard").removeClass("open-it")})});

  $(document).ready(function(){$(".menuquote").click(function(){$("body").addClass("")});$(".close").click(function(){$("body").removeClass("")});
  $(".menuquote").click(function(){$(".menuside").addClass("open-it")});$(".close").click(function(){$(".menuside").removeClass("open-it")})});
