jQuery(function($){
  /* === frontend === */
  var the_frameRate = parseInt($('input[name$="frame_rate"]').val())
  the_speed = parseInt($('input[name$="speed"]').val()),
  the_direction = $('input[name$="direction"]').val();
  
  if ( $('body').find('#yinstagram-scroller').length == 1 ) {
    // simplyScroller
    $('#yinstagram-scroller').simplyScroll({
      customClass: 'vert',
      frameRate: the_frameRate,
      speed: the_speed,
      orientation: 'vertical',
      direction: the_direction,
      pauseOnHover: false
    });
    
    //Triggers when document first loads
    imageResize();
    
    //Adjusts image when browser resized
    $(window).bind("resize", function(){
      imageResize();
    });
  }
  
  if ( ($('body').find('.widget_yinstagram').length == 1) && ($('body').find('#colorbox-options').length == 1) ) {
    var colorbox_options = $('#colorbox-options').val().split(';');
    if (colorbox_options[0] == 'on') {
      if ( colorbox_options[1] == 'fade' ) {
        $(".yinstagram-cbox").colorbox({rel:'yinstagram-cbox', transition:'fade'});
      } else if ( colorbox_options[1] == 'slideshow' ) {
        $(".yinstagram-cbox").colorbox({rel:'yinstagram-cbox', slideshow:true});
      } else {
        $(".yinstagram-cbox").colorbox({rel:'yinstagram-cbox'});
      }
    }
  }
  /* end of frontend */
  
  /* === backend === */
  if ( $('.wrap').find('#display_the_following_hashtags').length == 1 ) {
    //Display Your Images
    $('input[name*="display_your_images"]').click(function() {
      if ( $(this).val() != 'hashtag' ) {
        $('input:hidden[name=dyi_radio_previous_value]').val( $(this).val() );
      }
      
      if ( $(this).val() == 'hashtag' ) {
        $('input:radio[name=option_display_the_following_hashtags]').filter('[value=1]').prop('checked', true);
        $('#showHashtags').attr('style', 'display: block;');
      } else {
        $('input:radio[name=option_display_the_following_hashtags]').filter('[value=0]').prop('checked', true);
        $('#showHashtags').attr('style', 'display: none;');
      }
    });
    
    //Display The Following Hashtags
    $('input[name*="option_display_the_following_hashtags"]').click(function() {
      var showHashtags = $(this).val(),
      dyi_radios = $('input:radio[name=display_your_images]'),
      dyi_radio_previous_value = $('input:hidden[name=dyi_radio_previous_value]').val();
      
      if ( showHashtags == '1' ) {
        $('#showHashtags').attr('style', 'display: block;');
        dyi_radios.filter('[value=hashtag]').prop('checked', true);
      } else {
        $('#showHashtags').attr('style', 'display: none;');
        
        if ( dyi_radio_previous_value == 'hashtag' ) { dyi_radio_previous_value = 'recent' }
        dyi_radios.filter('[value='+dyi_radio_previous_value+']').prop('checked', true);
      }
    });
  }
  $('.yinstagram-colorbox').live('change', function(){
    if(this.checked) {
      $('.yinstagram-colorbox-options').prop('disabled', false);
    } else {
      $('.yinstagram-colorbox-options').prop('disabled', true);
    }
  });
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

/* === backend functions === */

/* end of backend functions */
