jQuery(function($){
  /* === frontend === */
  if ( $('body').find('#yinstagram-scroller').length === 1 ) {
    var yinstagram_shortcode_settings = $.parseJSON( $('#yinstagram-shortcode-settings').val() );
    
    // simplyScroller
    $('#yinstagram-scroller').simplyScroll({
      customClass: 'vert',
      frameRate: parseInt(yinstagram_shortcode_settings['frame_rate']),
      speed: parseInt(yinstagram_shortcode_settings['speed']),
      orientation: 'vertical',
      direction: yinstagram_shortcode_settings['direction'],
      pauseOnHover: false
    });
    
    //Triggers when document first loads
    imageResize();
    
    //Adjusts image when browser resized
    $(window).bind("resize", function(){
      imageResize();
    });
    
    var yinstagram_shortcode_images = $.parseJSON( $('#yinstagram-shortcode-images').val() ),
      timeDelayS = 0,
      contentwidth = parseInt($('.simply-scroll-clip').width()),
      dimensions = ( contentwidth * 24.9 ) / 100;
    
    if ( yinstagram_shortcode_images ) {
      $.each(yinstagram_shortcode_images, function(i, item) {
        
        setTimeout( function() {
          $('.load_s-'+yinstagram_shortcode_images[i].id).html('<em style="width: ' + dimensions + 'px; height: ' + dimensions + 'px;"></em>');
          
          $( '<img class="img_s-' + yinstagram_shortcode_images[i].id + '" title="' + yinstagram_shortcode_images[i].title + '" src="' + yinstagram_shortcode_images[i].src + '" style="display: none; width: ' + dimensions + 'px; height: ' + dimensions + 'px;">' ).load(function() {
            $( '.load_s-'+yinstagram_shortcode_images[i].id ).replaceWith(this);
            $('.img_s-'+yinstagram_shortcode_images[i].id).fadeIn();
          });
        }, timeDelayS);
        
        timeDelayS = timeDelayS + 512;
      });
    }
  }
  
  if ( ($('body').find('.widget_yinstagram').length === 1) && ($('body').find('#yinstagram-widget-settings').length === 1) ) {
    var yinstagram_widget_settings = $.parseJSON( $('#yinstagram-widget-settings').val() ),
    yinstagram_widget_images = $.parseJSON( $('#yinstagram-widget-images').val() ),
    timeDelayW = 0;
    
    if ( yinstagram_widget_images ) {
      $.each(yinstagram_widget_images, function(i, item) {
        setTimeout( function() {
          $('.load_w-'+yinstagram_widget_images[i].id).html('<em style="width: ' + yinstagram_widget_settings['dimensions'] + 'px; height: ' + yinstagram_widget_settings['dimensions'] + 'px;"></em>');
          
          $( '<img class="img_w-' + yinstagram_widget_images[i].id + '" src="' + yinstagram_widget_images[i].src + '" style="display: none; width: ' + yinstagram_widget_settings['dimensions'] + 'px; height: ' + yinstagram_widget_settings['dimensions'] + 'px;">' ).load(function() {
            $( '.load_w-'+yinstagram_widget_images[i].id ).replaceWith(this);
            $('.img_w-'+yinstagram_widget_images[i].id).fadeIn();
          });
          
        }, timeDelayW);
        
        timeDelayW = timeDelayW + 512;
      });
    }
    
    if (yinstagram_widget_settings['colorbox_status'] === 'on') {
      if ( yinstagram_widget_settings['colorbox_effect'] === 'fade' ) {
        $(".yinstagram-cbox").colorbox({rel:'yinstagram-cbox', transition:'fade'});
      } else if ( yinstagram_widget_settings['colorbox_effect'] === 'slideshow' ) {
        $(".yinstagram-cbox").colorbox({rel:'yinstagram-cbox', slideshow:true});
      } else {
        $(".yinstagram-cbox").colorbox({rel:'yinstagram-cbox'});
      }
    }
  }
  /* end of frontend */
  
  /* === backend === */
  if ( $('.wrap').find('#display_the_following_hashtags').length === 1 ) {
    //Display Your Images
    $('input[name*="display_your_images"]').click(function() {
      if ( $(this).val() !== 'hashtag' ) {
        $('input:hidden[name=dyi_radio_previous_value]').val( $(this).val() );
      }
      
      if ( $(this).val() === 'hashtag' ) {
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
      
      if ( showHashtags === '1' ) {
        $('#showHashtags').attr('style', 'display: block;');
        dyi_radios.filter('[value=hashtag]').prop('checked', true);
      } else {
        $('#showHashtags').attr('style', 'display: none;');
        
        if ( dyi_radio_previous_value === 'hashtag' ) { dyi_radio_previous_value = 'recent'; }
        dyi_radios.filter('[value='+dyi_radio_previous_value+']').prop('checked', true);
      }
    });
  }
  $(document.body).on('change', '.yinstagram-colorbox' ,function(){
    if(this.checked) {
      $('.yinstagram-colorbox-options').prop('disabled', false);
    } else {
      $('.yinstagram-colorbox-options').prop('disabled', true);
    }
  });
  $('#yinstagram-help-tab').on('click', function(e) {
      $('#contextual-help-link').trigger('click');
      $("html, body").animate({ scrollTop: $('#wpbody').offset().top }, 500);
      e.preventDefault();
    });
  /* end of backend */
});

/* === frontend functions === */
function imageResize() {
  jQuery(function($){
    var contentwidth = parseInt($('.simply-scroll-clip').width()),
      dimensions = ( contentwidth * 24.9 ) / 100;
      
    $('#yinstagram-scroller li img').attr( 'style', 'width: '+dimensions+'px; height:'+dimensions+'px;');
    
    $('#yinstagram-scroller li span em').attr( 'style', 'width: '+dimensions+'px; height:'+dimensions+'px;');
    
    $('.vert .simply-scroll-list li').attr( 'style', 'height:'+dimensions+'px;');
  });
}
/* end of frontend functions */

/* === backend functions === */

/* end of backend functions */
