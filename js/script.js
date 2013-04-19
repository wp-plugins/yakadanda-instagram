jQuery(function($){
  /* === frontend === */
  var the_frameRate = parseInt($('input[name$="frame_rate"]').val())
  the_speed = parseInt($('input[name$="speed"]').val()),
  the_direction = $('input[name$="direction"]').val();
  
  if ( $('body').find('#yinstagram-scroller').length == 1 ) {
    // simplyScroller
    $('#yinstagram-scroller').simplyScroll({
			customClass:'vert',
      frameRate:the_frameRate,
      speed:the_speed,
      orientation:'vertical',
      direction:the_direction,
			pauseOnHover: false
		});
    
    //Triggers when document first loads
    imageResize();
    
    //Adjusts image when browser resized
    $(window).bind("resize", function(){
      imageResize();
    });
  }
  /* end of frontend */
  
  /* === backend === */
  if ( $('.wrap').find('#display_the_following_hashtags').length == 1 ) {
    $('input[name*="option_display_the_following_hashtags"]').click(function() {
      var showHashtags = $(this).val();
      if (showHashtags == 1) {
        $('#showHashtags').attr('style', 'display: block;');
      } else {
        $('#showHashtags').attr('style', 'display: none;');
      }
    });
  }
  
  if ( $('.wrap').find('input[name$="ydo[header_menu_color]"]').length == 1 ) {
    var f = $.farbtastic('#picker'),
      p = $('#picker').css('opacity', 0.25),
      selected;
    $('.colorwell').each(function () {
      f.linkTo(this);
      $(this).css('opacity', 0.75);
    }).focus(function() {
      if (selected) {
        $(selected).css('opacity', 0.75).removeClass('colorwell-selected');
      }
      f.linkTo(this);
      p.css('opacity', 1);
      $(selected = this).css('opacity', 1).addClass('colorwell-selected');
    });
  }
  /* end of backend */
});

/* === frontend functions === */
function imageResize() {
  jQuery(function($){
    var contentwidth = parseInt($('.simply-scroll-clip').width()),
      dimensions = ( contentwidth * 24.9 ) / 100;
      
    $('#yinstagram-scroller li img').attr( 'style', 'width: '+dimensions+'px; height:'+dimensions+'px;');
    $('.vert .simply-scroll-list li').attr( 'style', 'height:'+dimensions+'px;');
  });
}
/* end of frontend functions */
