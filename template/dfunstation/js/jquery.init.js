(function($){
  "use strict"

  $.fn.exist = function(){ return $(this).length > 0; }

    if ($('#masonry-e').exist()){
      $(function(){

          var $container = $('#masonry-e');

          $container.imagesLoaded(function(){
              $('.items','#masonry-e').wookmark({
                align: 'left',
                autoResize: false,
                comparator: null,
                container: $('#masonry-e'),
                direction: undefined,
                ignoreInactiveItems: true,
                itemWidth: 290,
                fillEmptySpace: false,
                flexibleWidth: 0,
                offset:40,
                onLayoutChanged: undefined,
                outerOffset: 0,
                possibleFilters: [],
                resizeDelay: 50,
                verticalOffset: 25
              });
          });
      });
    }

if ($('#masonry-e2').exist()){
      $(function(){

          var $container = $('#masonry-e2');

          $container.imagesLoaded(function(){
              $('.items','#masonry-e2').wookmark({
                align: 'left',
                autoResize: false,
                comparator: null,
                container: $('#masonry-e2'),
                direction: undefined,
                ignoreInactiveItems: true,
                itemWidth: 290,
                fillEmptySpace: false,
                flexibleWidth: 0,
                offset:40,
                onLayoutChanged: undefined,
                outerOffset: 0,
                possibleFilters: [],
                resizeDelay: 50,
                verticalOffset: 25
              });
          });
      });
    }

if ($('#masonry-col2').exist()){
    $(function(){

        var $container = $('#masonry-col2');

        $container.imagesLoaded(function(){
            $('.items','#masonry-col2').wookmark({
              align: 'left',
              autoResize: false,
              comparator: null,
              container: $('#masonry-col2'),
              direction: undefined,
              ignoreInactiveItems: true,
              itemWidth: 500,
              fillEmptySpace: false,
              flexibleWidth: 0,
              offset:40,
              onLayoutChanged: undefined,
              outerOffset: 0,
              possibleFilters: [],
              resizeDelay: 50,
              verticalOffset: 25
            });
        });
    });
  }

    if ($('.shoutbox-container').exist()) {
       $(window).load(function(){
        $.mCustomScrollbar.defaults.scrollButtons.enable=true; //enable scrolling buttons by default

        $(".shoutbox-container").mCustomScrollbar({
          theme: "inset-dark"
        });
      });
    }

    if ($('.mscroller').exist()) {
       $(window).load(function(){
        $.mCustomScrollbar.defaults.scrollButtons.enable=true; //enable scrolling buttons by default

        $(".mscroller").mCustomScrollbar({
          theme: "inset-dark"
        });
      });
    }


  $(function(){
    $(".tooltips").tooltip();

    if ($('.marquees').exist()) {
      $('.marquees').marquee('pointer').mouseover(function () {
        $(this).trigger('stop');
      }).mouseout(function () {
        $(this).trigger('start');
      }).mousemove(function (event) {
        if ($(this).data('drag') == true) {
          this.scrollLeft = $(this).data('scrollX') + ($(this).data('x') - event.clientX);
        }
      }).mousedown(function (event) {
        $(this).data('drag', true).data('x', event.clientX).data('scrollX', this.scrollLeft);
      }).mouseup(function () {
        $(this).data('drag', false);
      });
    }

    if ($('#slider-main').exist()) {
     var owlMain = $("#slider-main");
      owlMain.owlCarousel({
        slideSpeed : 300,
		autoPlay : true,
        pagination: true,
        singleItem: true
      });

      $(".owl-prev").on('click', function(e) {e.preventDefault();owlMain.trigger('owl.prev')});
      $(".owl-next").on('click', function(e) {e.preventDefault();owlMain.trigger('owl.next')});
    }
	
	if ($('#slider-banner').exist()) {
     var owlMain = $("#slider-banner");
        owlMain.owlCarousel({
        slideSpeed : 300,
        singleItem: true,
        autoPlay : true,
        transitionStyle : "fade",
        pagination : false
      });

      $(".owl-prev").on('click', function(e) {e.preventDefault();owlMain.trigger('owl.prev')});
      $(".owl-next").on('click', function(e) {e.preventDefault();owlMain.trigger('owl.next')});
    }

    if ($('#slider-radio').exist()) {
      var owlRadio = $("#slider-radio");
      owlRadio.owlCarousel({
        slideSpeed : 300,
        pagination: false,
        singleItem: true
      });

      $(".radio-prev").on('click', function(e) {e.preventDefault();owlRadio.trigger('owl.prev')});
      $(".radio-next").on('click', function(e) {e.preventDefault();owlRadio.trigger('owl.next')});
    }

    if ($('#slider-channel').exist()) {
      var owlChannel = $("#slider-channel");
      owlChannel.owlCarousel({
        items: 4,
        slideSpeed : 300,
        pagination: false,
        responsive : false
      });

      $(".radio-prev").on('click', function(e) {e.preventDefault();owlChannel.trigger('owl.prev')});
      $(".radio-next").on('click', function(e) {e.preventDefault();owlChannel.trigger('owl.next')});
    }

    if ($('.sign-form').exist()){
    /* ===== Password Lost ===== */

    $('.pwd-lost > .pwd-lost-q > a').on('click', function() {
      $(".pwd-lost > .pwd-lost-q").toggleClass("show hidden");
      $(".pwd-lost > .pwd-lost-f").toggleClass("hidden show animated fadeIn");
          return false;
    });

    /* ===== Sign Up popovers ===== */

    $(function(){
        $('#name').popover();
    });

    $(function(){
        $('#username').popover();
    });

    $(function(){
        $('#email').popover();
    });

    $(function(){
        $('#password').popover();
    });

    $(function(){
        $('#repeat-password').popover();
    });
    }

     if($('.fancybox').exist())
    {
      $(".fancybox").fancybox();
    }

    if ($('.i-checks').exist()){
      $('.i-checks').iCheck({
            checkboxClass: 'icheckbox_square-green',
            radioClass: 'iradio_square-green',
      });
    }

    $("#btn-browse").click(function(e) {
       e.preventDefault();
       $("#filePhoto").trigger('click');
    });

    $('#btn-remove').on('click',function(){
       $('.media-reply .icheckbox_square-green').fadeIn("fast");
       $('.showremove').fadeIn("fast");
    })

  });

  $(document).ready(function(){
      var $del = $(".close");

      $del.on('click',function(e){
        e.preventDefault();

        var $id = $(this).attr('data-id');
        var $postId = $(this).attr('data-postid');

        if ($id.length > 0)
        {
          $.ajax({
            url : 'http://seftcenter.com/member/delkomen/'+ $postId + '/' + $id
          });
        }
      });
  });

  $(function(){
    var $e = $('.expanded-conversation');

    if (!$e.hasClass('collapsed-conversation')){
        $('.show-conversation').text("Hide Conversation");
      }

    $('.show-conversation').on('click',function(event){
      $(this).closest('.stream-item')
          .find('ol.expanded-conversation')
          .toggleClass('collapsed-conversation','collapse-conversation');

      ($(this).text() === "Show Conversation") ? $(this).text("Hide Conversation") : $(this).text("Show Conversation");
      event.preventDefault();
    });
    $('.stream-media-img').on('click',function(event){
      $(this).toggleClass('full');
      event.preventDefault();
    });

    $('.del-action').on({
      click : function(event){
        $(this).closest('.stream-item').fadeOut('fast');
        event.preventDefault();

      }
    });

    if ($('.input-qty').exist()) {
      $('.input-qty').TouchSpin();
    }

    $('ul.list-root ul.hidden').hide();
    $('ul.list-root a').click(function(event) {
      if ( $(this).next().is('ul') == true ) {
        event.preventDefault();
        if ( $(this).next().hasClass('level-2') == true ) {
          var className = 'level-2';
        }
        if ( $(this).next().hasClass('level-3') == true ) {
          var className = 'level-3';
        }
        if ( $(this).hasClass('expanded') == false ) {
          $('ul.' + className + '.visible')
          .slideUp(200)
          .toggleClass('visible hidden')
          .prev('a')
          .toggleClass('expanded');
          $(this)
          .toggleClass('expanded')
          .next('ul')
          .slideDown(200)
          .toggleClass('visible hidden');
        } else {
          $('ul.' + className + '.visible')
          .slideUp(200)
          .toggleClass('visible hidden')
          .prev('a')
          .toggleClass('expanded');
        }
      }
    });
  });

 $(window).load( function() {
 if ($('.sp-wrap').exist()) {
      $('.sp-wrap').smoothproducts()();
    }
  });
})(jQuery);
