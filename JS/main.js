$(document).ready(function(){
  $(window).scrollTop(0);
  $('body').css('overflow-y', 'hidden');
    setTimeout(function(){
      $('#menu a').addClass('bounceInLeft');
      $('#menu a').css('display','block');
      $('video').animate({
        opacity: 0
      },100,function(){
        $('#sec .ls').animate({
          opacity: 1,
          'z-index': 100
      },100);
        $('#sec .ls').addClass('bounceInDown');
        $('#sec p').css('display','block');
        $('#sec #text').addClass('lightSpeedIn');
        $('#sec #text1').addClass('rollIn');
        $('#sec #text2').addClass('bounceInRight');
        $('#sec #indic').css('display','block');
        $('#sec #indic').addClass('bounce');
        $('body').css('overflow-y','visible');
      });
  },100);
  $('#sec #indic').click(function(){
    $(window).scrollTop($('#sec1').offset().top);
  });

  $('.owl-carousel').owlCarousel({
    loop:true,
    margin:10,
    responsive:{
        0:{
            nav:true,
            items:1
        },
        600:{
            nav:true,
            items:3
        },
        1000:{
            nav:true,
            items:5
        }
    },
    autoplay:true,
    autoplayTimeout:2000,
    autoplayHoverPause:true
  });

  $('#sec3 .button').click(function(){
    window.location.href="form.html";
  });

  $('#sec4 .form .close').click(function(){
    $('#sec4 .blur').toggleClass('on');
    $('#sec4 .form .close').toggleClass('fa-close');
    $('#sec4 .form .close').toggleClass('fa-comments');
    $('#sec4 .form').toggleClass('active');
  });
});
