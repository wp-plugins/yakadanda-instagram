jQuery(function($){
  /*
   * frontend
   */
  // auto scroll shortcode
  if ( $('body').find('.yinstagram-shortcode-auto').length >= 1 ) {
    
    $('.yinstagram-shortcode-auto').each(function() {
      var ySauto = this,
      yinstagram_shortcode_settings = $.parseJSON( $('.yinstagram-shortcode-settings-au', ySauto).val() );
      
      // simplyScroller
      $('.yinstagram-scroller-auto', ySauto).simplyScroll({
        customClass: 'vert',
        frameRate: parseInt(yinstagram_shortcode_settings['frame_rate']),
        speed: parseInt(yinstagram_shortcode_settings['speed']),
        orientation: 'vertical',
        direction: yinstagram_shortcode_settings['direction'],
        pauseOnHover: false
      });
      
      //Triggers when document first loads
      resizeShortcodeImagesAuto();

      //Adjusts image when browser resized
      $(window).bind('resize', function(){
        resizeShortcodeImagesAuto();
      });
      
      var yinstagram_shortcode_images_au = $.parseJSON( $('.yinstagram-shortcode-images-auto', ySauto).val() ),
        timeDelayAuS = 0,
        contentWidthAu = parseInt($('.simply-scroll-clip', ySauto).width()),
        auDimensions = ( contentWidthAu * 24.9 ) / 100;
      
      if (yinstagram_shortcode_images_au) {
        $.each(yinstagram_shortcode_images_au, function(i, item) {

          setTimeout( function() {
            $('.load_as-'+yinstagram_shortcode_images_au[i].id. yS).html('<em style="width: ' + auDimensions + 'px; height: ' + auDimensions + 'px;"></em>');

            $('<img class="img_as-' + yinstagram_shortcode_images_au[i].id + '" title="' + yinstagram_shortcode_images_au[i].title + '" src="' + yinstagram_shortcode_images_au[i].src + '" style="display: none; width: ' + auDimensions + 'px; height: ' + auDimensions + 'px;">').load(function() {
              $('.load_as-'+yinstagram_shortcode_images_au[i].id, ySauto).replaceWith(this);
              $('.img_as-'+yinstagram_shortcode_images_au[i].id, ySauto).fadeIn();
            });
          }, timeDelayAuS);

          timeDelayAuS = timeDelayAuS + 512;
        });
      }
      
    });
    
  }
  // end of auto scroll shortcode
  
  // infinite scroll shortcode
  if ( $('body').find('.yinstagram-shortcode-infinite').length >= 1 ) {
    
    $('.yinstagram-shortcode-infinite').each(function() {
      var ySinfinite = this,
        yinstagram_shortcode_settings = $.parseJSON( $('.yinstagram-shortcode-settings-inf', ySinfinite).val() ),
        yinstagram_shortcode_images_au_inf = $.parseJSON( $('.yinstagram-shortcode-images-infinite', ySinfinite).val() ),
        contentWidthInf = parseInt($('.yinstagram-scroller-infinite', ySinfinite).width()),
        infDimensions = ( contentWidthInf * 24.9 ) / 100;
      
      //Triggers when document first loads
      resizeShortcodeImagesInfinite();

      //Adjusts image when browser resized
      $(window).bind("resize", function(){
        resizeShortcodeImagesInfinite();
      });
      
      if ( yinstagram_shortcode_images_au_inf ) {
        loadInfiniteImages(yinstagram_shortcode_images_au_inf, infDimensions, ySinfinite);
      }

      $(window).scroll(function() {
        if ($(window).scrollTop() + $(window).height() === $(document).height()) {
          loadInfiniteImages(yinstagram_shortcode_images_au_inf, infDimensions, ySinfinite);
        }
      });
      
    });
    
    var yinstagram_shortcode_settings = $.parseJSON( $('.yinstagram-shortcode-settings-inf:first-child').val() );
    
    // shortcode lightbox
    modalDialog(yinstagram_shortcode_settings);
  }
  // end of infinite scroll shortcode
  
  // images widget
  if ( ($('body').find('.widget_yinstagram').length >= 1) && ($('body').find('.yinstagram-widget-settings').length >= 1) ) {
    
    $('.widget_yinstagram').each(function() {
      var yinstagram_widget_settings = $.parseJSON( $('.yinstagram-widget-settings', this).val() ),
        yinstagram_widget_images = $.parseJSON( $('.yinstagram-widget-images', this).val() ),
        timeDelayW = 0;
      
      if ( yinstagram_widget_images ) {
        $.each(yinstagram_widget_images, function(i, item) {
          setTimeout( function() {
            $('.load_w-'+yinstagram_widget_images[i].id).html('<em style="width: ' + yinstagram_widget_settings['dimensions'] + 'px; height: ' + yinstagram_widget_settings['dimensions'] + 'px;"></em>');

            $( '<img class="img_w-' + yinstagram_widget_images[i].id + '" title="' + yinstagram_widget_images[i].title +'" src="' + yinstagram_widget_images[i].src + '" style="display: none; width: ' + yinstagram_widget_settings['dimensions'] + 'px; height: ' + yinstagram_widget_settings['dimensions'] + 'px;">' ).load(function() {
              $( '.load_w-'+yinstagram_widget_images[i].id ).replaceWith(this);
              $('.img_w-'+yinstagram_widget_images[i].id).fadeIn();
            });

          }, timeDelayW);

          timeDelayW = timeDelayW + 512;
        });
      }
    });
    
    var yinstagram_widget_settings = $.parseJSON( $('.yinstagram-widget-settings:first-child').val() );
    
    // widget lightbox
    modalDialog(yinstagram_widget_settings);
  }
  // end of image widget
  
  // profile widget
  if ( $('.widget_yinstagram').find('.yinstagram_profile').length >= 1 ) {
    resizeWidgetProfileImages();
    
    //Adjusts image when browser resized
    $(window).bind("resize", function(){
      resizeWidgetProfileImages();
    });
  }
  
  // end of profile widget
  
  // remove # (Octothorpe, Number, Pound, sharp, or Hash) on thickbox modal dialog
  $(document.body).on('click', '#TB_closeWindowButton',function(e) {
    e.preventDefault();
  });
  
  /*
   * backend
   */
  // settings page
  if ( $('.wrap').find('#display_the_following_hashtags').length === 1 ) {
    // Display Your Images
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
    
    // Display The Following Hashtags
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
    
    // Confirmation dialog
    $("#dialog-confirm").dialog({
      autoOpen: false,
      resizable: false,
      draggable: false,
      height: 200,
      width: 300,
      modal: true,
      buttons: {
        "OK": function() {
          $(this).dialog("close");
          var data = {
            action: 'yinstagram_restore_settings'
          };
          $.post(ajax_object.ajax_url, data, function(response) {
            window.location.replace(response);
          });
        },
        Cancel: function() {
          $(this).dialog("close");
        }
      }
    });
  }
  // end of settings page
  
  // help tab
  $('#yinstagram-setup-help-tab').on('click', function(e) {
    if ($('#screen-meta').is(":hidden")) {
      $('#contextual-help-link').trigger('click');
    }
    $('#tab-link-yinstagram-setup a').trigger('click');
    $("html, body").animate({scrollTop: $('#wpbody').offset().top}, 500);
    e.preventDefault();
  });
  $('#yinstagram-shortcode-help-tab').on('click', function(e) {
    if ($('#screen-meta').is(":hidden")) {
      $('#contextual-help-link').trigger('click');
    }
    $('#tab-link-yinstagram-shortcode a').trigger('click');
    $("html, body").animate({scrollTop: $('#wpbody').offset().top}, 500);
    e.preventDefault();
  });
  
  // widget
  $(document.body).on('change', '.yinstagram-type' ,function() {
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
  
  // dismiss notification
  $('#yinstagram-dismiss').click(function(e){
    var data = {
      action: 'yinstagram_dismiss'
    };
    $.post(ajax_object.ajax_url, data, function(response) {
      $('.yinstagram-notice').remove();
    });
    e.preventDefault();
  });
  
  // logout from google api
  $('#yinstagram-logout').click(function(e){
    var data = {
      action: 'yinstagram_logout'
    };
    $.post(ajax_object.ajax_url, data, function(response) {
      window.location.replace(response);
    });
    e.preventDefault();
  });
  
  // reset settings
  $('#yinstagram-restore-settings').click(function(e){
    $('#dialog-confirm').dialog('open');
    e.preventDefault();
  });
  
  // reset display options
  $('#yinstagram-restore-display-options').click(function(e){
    var data = {
      action: 'yinstagram_restore_display_options'
    };
    $.post(ajax_object.ajax_url, data, function(response) {
      window.location.replace(response);
    });
    e.preventDefault();
  });
  
  // scroll options
  $('#scroll').change(function() {
    var arr_au = [ 2, 3, 4, 5 ];
    if ($(this).val() === 'auto') {
      $.each(arr_au, function(key, value) {
        $('table.form-table tbody tr:nth-child(' + value + ')').show();
      });
    } else {
      $.each(arr_au, function(key, value) {
        $('table.form-table tbody tr:nth-child(' + value + ')').hide();
      });
    }
  });
  
  // lightbox options
  $('#lightbox').change(function() {
    var arr_cb = [ 7, 8 ];
    if ($(this).val() === 'colorbox') {
      $.each(arr_cb, function(key, value) {
        $('table.form-table tbody tr:nth-child(' + value + ')').show();
      });
    } else {
      $.each(arr_cb, function(key, value) {
        $('table.form-table tbody tr:nth-child(' + value + ')').hide();
      });
    }
  });
  
});

/*
 * frontend functions
 */
function resizeShortcodeImagesAuto() {
  jQuery(function($){
    var contentWidthAu = parseInt($('.simply-scroll-clip').width()),
      AuDimensions = ( contentWidthAu * 24.9 ) / 100;
      
    $('.yinstagram-scroller-auto li img').attr( 'style', 'width: ' + AuDimensions + 'px; height: ' + AuDimensions + 'px;');
    
    $('.yinstagram-scroller-auto li span em').attr( 'style', 'width: ' + AuDimensions + 'px; height: ' + AuDimensions + 'px;');
    
    $('.vert .simply-scroll-list li').attr( 'style', 'height:' + AuDimensions + 'px;');
  });
}

function resizeShortcodeImagesInfinite() {
  jQuery(function($){
    var contentWidthInf = parseInt($('.yinstagram-scroller-infinite').width()),
      infDimensions = ( contentWidthInf * 24.9 ) / 100;
      
    $('.yinstagram-scroller-infinite li img').attr( 'style', 'width: ' + infDimensions + 'px; height: ' + infDimensions + 'px;');
    
    $('.yinstagram-scroller-infinite li span em').attr( 'style', 'width: ' + infDimensions + 'px; height: ' + infDimensions +'px;');
    
    
  });
}

function resizeWidgetProfileImages() {
  jQuery(function($){
    var contentWidthAu = parseInt($('.yinstagram_profile ul.images').width()),
    dimensions = ( contentWidthAu * 24.9 ) / 100;
    
    $('.yinstagram_profile ul.images li img').attr( 'style', 'width: '+dimensions+'px; height:'+dimensions+'px;');
  });
}

function loadInfiniteImages(yinstagram_shortcode_images_au_inf, infDimensions, ySinfinite) {
  jQuery(function($){
    var iBreak = parseInt($('.yinstagram-inf-images-i', ySinfinite).val(), 10),
      timeDelayInfS = 0;
    
    $.each(yinstagram_shortcode_images_au_inf, function(i, item) {
      i = i + ( iBreak - 15 );
      
      if (typeof yinstagram_shortcode_images_au_inf[i] === "undefined") {
        return false;
      }
      
      setTimeout( function() {
        $('.load_is-' + yinstagram_shortcode_images_au_inf[i].id).html('<em style="width: ' + infDimensions + 'px; height: ' + infDimensions + 'px;"></em>');
        
        $('<img class="img_is-' + yinstagram_shortcode_images_au_inf[i].id + '" title="' + yinstagram_shortcode_images_au_inf[i].title + '" src="' + yinstagram_shortcode_images_au_inf[i].src + '" style="display: none; width: ' + infDimensions + 'px; height: ' + infDimensions + 'px;">').load(function() {
          $('.load_is-' + yinstagram_shortcode_images_au_inf[i].id).replaceWith(this);
          $('.img_is-' + yinstagram_shortcode_images_au_inf[i].id).fadeIn();
        });
      }, timeDelayInfS);
      
      timeDelayInfS = timeDelayInfS + 512;
      
      if ( i === iBreak ) {
        iBreak = iBreak + 16;
        $('.yinstagram-inf-images-i', ySinfinite).val( iBreak );
        
        return false;
      }
    });
  });
}

function modalDialog(boxOptions) {
  jQuery(function($){
    if (boxOptions['lightbox'] === 'colorbox' ) {
      if (boxOptions['colorbox_effect'] === 'fade') {
        $(".yinstagram-lbox").colorbox({rel: 'yinstagram-lbox', transition: 'fade', scalePhotos: true, maxHeight: '90%'});
      } else if (boxOptions['colorbox_effect'] === 'slideshow') {
        $(".yinstagram-lbox").colorbox({rel: 'yinstagram-lbox', slideshow: true, scalePhotos: true, maxHeight: '90%'});
      } else {
        $(".yinstagram-lbox").colorbox({rel: 'yinstagram-lbox', scalePhotos: true, maxHeight: '90%'});
      }
    }
  });
}

// twitter
!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="https://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");

// google+ button
(function() {
  var po = document.createElement('script');
  po.type = 'text/javascript';
  po.async = true;
  po.src = 'https://apis.google.com/js/platform.js';
  var s = document.getElementsByTagName('script')[0];
  s.parentNode.insertBefore(po, s);
})();
