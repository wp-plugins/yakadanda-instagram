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
    resizeShortcodeImages();
    
    //Adjusts image when browser resized
    $(window).bind("resize", function(){
      resizeShortcodeImages();
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
  
  if ( ($('body').find('.widget_yinstagram').length >= 1) && ($('body').find('#yinstagram-widget-settings').length === 1) ) {
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
  
  if ( $('.widget_yinstagram').find('.yinstagram_profile').length >= 1 ) {
    resizeWidgetImages();
  }
  
  /* end of frontend */
  
  /* === backend === */
  // settings page and display options page
  if ( $('.wrap').find('#display_the_following_hashtags').length === 1 ) {
    //Display Your Images
    $('input[name*="display_images"]').click(function() {
      if ( $(this).val() !== 'hashtag' ) {
        $('input:hidden[name=di_radio_previous_value]').val( $(this).val() );
      }
      
      if ( $(this).val() === 'hashtag' ) {
        $('input:radio[name=option_display_the_following_hashtags]').filter('[value=1]').prop('checked', true);
        $('#showHashtags').attr('style', '');
      } else {
        $('input:radio[name=option_display_the_following_hashtags]').filter('[value=0]').prop('checked', true);
        $('#showHashtags').attr('style', 'display: none;');
      }
      
      if ( $(this).val() === 'recent' ) {
        $('#showUsername').attr('style', '');
      } else {
        $('#showUsername').attr('style', 'display: none;');
      }
      
    });
    
    //Display The Following Hashtags
    $('input[name*="option_display_the_following_hashtags"]').click(function() {
      var showHashtags = $(this).val(),
      di_radios = $('input:radio[name=display_images]'),
      di_radio_previous_value = $('input:hidden[name=di_radio_previous_value]').val();
      
      if ( showHashtags === '1' ) {
        $('#showHashtags').attr('style', '');
        di_radios.filter('[value=hashtag]').prop('checked', true);
      } else {
        $('#showHashtags').attr('style', 'display: none;');
        
        if ( di_radio_previous_value === 'hashtag' ) { di_radio_previous_value = 'recent'; }
        di_radios.filter('[value='+di_radio_previous_value+']').prop('checked', true);
      }
    });
  }
 
  $('#yinstagram-help-tab').on('click', function(e) {
    $('#contextual-help-link').trigger('click');
    $("html, body").animate({scrollTop: $('#wpbody').offset().top}, 500);
    e.preventDefault();
  });
  // widget
  $(document.body).on('change', '.yinstagram-type' ,function(){
    var selectboxID = $(this).attr('id');
      widgetID = selectboxID.replace(/[^\d.]/g, '');
    
    if ($(this).val() === 'images') {
      $('.widget-yinstagram-'+widgetID+'-type-container').show();
    } else {
      $('.widget-yinstagram-'+widgetID+'-type-container').hide();
    }
  });
  $(document.body).on('change', '.yinstagram-display-images' ,function(){
    var selectboxID = $(this).attr('id');
      widgetID = selectboxID.replace(/[^\d.]/g, '');
    
    if ($(this).val() === 'tags') {
      $('#widget-yinstagram-'+widgetID+'-hashtags-container').show();
    } else {
      $('#widget-yinstagram-'+widgetID+'-hashtags-container').hide();
    }
    if ($(this).val() === 'recent') {
      $('#widget-yinstagram-'+widgetID+'-recent-container').show();
    } else {
      $('#widget-yinstagram-'+widgetID+'-recent-container').hide();
    }
    
  });
  $(document.body).on('change', '.yinstagram-colorbox' ,function(){
    var checkboxID = $(this).attr('id');
      widgetID = checkboxID.replace(/[^\d.]/g, '');
    
    if(this.checked) {
      $('#widget-yinstagram-'+widgetID+'-theme').prop('disabled', false);
      $('#widget-yinstagram-'+widgetID+'-effect').prop('disabled', false);
    } else {
      $('#widget-yinstagram-'+widgetID+'-theme').prop('disabled', true);
      $('#widget-yinstagram-'+widgetID+'-effect').prop('disabled', true);
    }
  });
  $('#yinstagram-dismiss').click(function(e){
    var data = {
      action: 'yinstagram_dismiss'
    };
    $.post(ajax_object.ajax_url, data, function(response) {
		$('.yinstagram-notice').remove();
	});
    e.preventDefault();
  });
  $('#yinstagram-logout').click(function(e){
    var data = {
      action: 'yinstagram_logout'
    };
    $.post(ajax_object.ajax_url, data, function(response) {
      location.reload();
	});
    e.preventDefault();
  });
  /* end of backend */
});

/* === frontend functions === */
function resizeShortcodeImages() {
  jQuery(function($){
    var contentwidth = parseInt($('.simply-scroll-clip').width()),
      dimensions = ( contentwidth * 24.9 ) / 100;
      
    $('#yinstagram-scroller li img').attr( 'style', 'width: '+dimensions+'px; height:'+dimensions+'px;');
    
    $('#yinstagram-scroller li span em').attr( 'style', 'width: '+dimensions+'px; height:'+dimensions+'px;');
    
    $('.vert .simply-scroll-list li').attr( 'style', 'height:'+dimensions+'px;');
  });
}
function resizeWidgetImages() {
  jQuery(function($){
    var contentwidth = parseInt($('.yinstagram_profile ul.images').width()),
    dimensions = ( contentwidth * 24.9 ) / 100;
    
    $('.yinstagram_profile ul.images li img').attr( 'style', 'width: '+dimensions+'px; height:'+dimensions+'px;');
  });
}

/* end of frontend functions */

/* === backend functions === */

/* end of backend functions */
