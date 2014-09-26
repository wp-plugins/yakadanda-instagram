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

      $('.yinstagram-load-more').click(function(e) {
        loadInfiniteImages(yinstagram_shortcode_images_au_inf, infDimensions, ySinfinite);
        e.preventDefault();
      });
      
    });
    
    var yinstagram_shortcode_settings = $.parseJSON( $('.yinstagram-shortcode-settings-inf:first').val() );
    
    // shortcode lightbox
    modalDialog(yinstagram_shortcode_settings);
  }
  // end of infinite scroll shortcode
  
  // images widget
  if ( ($('body').find('.widget_yinstagram').length >= 1) && ($('body').find('.yinstagram-widget-settings').length >= 1) ) {
    
    $('.widget_yinstagram').each(function() {
      var yinstagram_widget_settings = $.parseJSON( $('.yinstagram-widget-settings', this).val() ),
        yinstagram_widget_images = $.parseJSON( $('.yinstagram-widget-images', this).val() ),
        contentWidthAu = parseInt( $('.yinstagram_grid', this).width() ),
        timeDelayW = 0;
      
      if ( yinstagram_widget_images ) {
        $.each(yinstagram_widget_images, function(i, item) {
          setTimeout( function() {
            $('.load_w-'+yinstagram_widget_images[i].id).html('<em style="width: ' + yinstagram_widget_settings['dimensions'] + 'px; height: ' + yinstagram_widget_settings['dimensions'] + 'px;"></em>');

            if (yinstagram_widget_settings['dimensions'] === "") {
              yinstagram_widget_settings['dimensions'] = contentWidthAu;
            }

            $( '<img class="img_iw-' + yinstagram_widget_images[i].id + '" src="' + yinstagram_widget_images[i].src + '" title="' + yinstagram_widget_images[i].title + '" style="display: none; width: ' + yinstagram_widget_settings['dimensions'] + 'px; height: ' + yinstagram_widget_settings['dimensions'] + 'px;">' ).load(function() {
              $( '.load_w-'+yinstagram_widget_images[i].id ).replaceWith(this);
              $('.img_iw-'+yinstagram_widget_images[i].id).fadeIn();
            });

          }, timeDelayW);

          timeDelayW = timeDelayW + 512;
        });
      }
    });
    
    var yinstagram_widget_settings = $.parseJSON( $('.yinstagram-widget-settings:first').val() );
    
    // widget lightbox
    modalDialog(yinstagram_widget_settings);

    if (yinstagram_widget_settings['dimensions'] === "") {
      resizeWidgetImages();
      
      //Adjusts image when browser resized
      $(window).bind("resize", function(){
        resizeWidgetImages();
      });
    }

  }
  // end of image widget
  
  // profile widget
  if ( $('.widget_yinstagram').find('.yinstagram_profile').length >= 1 ) {
    resizeWidgetProfile();
    
    //Adjusts image when browser resized
    $(window).bind("resize", function(){
      resizeWidgetProfile();
    });
  }
  
  // end of profile widget
  
  // remove # (Octothorpe, Number, Pound, sharp, or Hash) on thickbox modal dialog
  $(document.body).on('click', '#TB_closeWindowButton',function(e) {
    e.preventDefault();
  });

  // qtip
  if ( $.isFunction($.fn.qtip) ) {
    $('body').on('mouseover', function(e) {
      // auto scroll
      if ( $('body').find('.yinstagram-shortcode-auto').length >= 1 ) {
        $('.yinstagram-scroller-auto img').each(function() {
          yinstagramQtip(this, 'img_as', 'qtip_as');
        });
      }
      // infinite scroll
      if ( $('body').find('.yinstagram-scroller-infinite').length >= 1 ) {
        $('.yinstagram-scroller-infinite img').each(function() {
          yinstagramQtip(this, 'img_is', 'qtip_is');
        });
      }
      // profile widget
      if ( $('body').find('.yinstagram_profile').length >= 1 ) {
        $('.yinstagram_profile img').each(function() {
          yinstagramQtip(this, 'img_pw', 'qtip_pw');
        });
        $('.yinstagram_circular').qtip('destroy');
      }
      // images widget
      if ( $('body').find('.yinstagram_grid').length >= 1 ) {
        $('.yinstagram_grid img').each(function() {
          yinstagramQtip(this, 'img_iw', 'qtip_iw');
        });
      }
      e.preventDefault();
    });
  }

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
    var arr_cb = [ 8, 9 ];
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
    $('.yinstagram-shortcode-auto').each(function() {
      var contentWidthAu = parseInt($('.simply-scroll-clip', this).width()),
        AuDimensions = ( contentWidthAu * 24.9 ) / 100;
      
      $('.yinstagram-scroller-auto li img', this).attr( 'style', 'width: ' + AuDimensions + 'px; height: ' + AuDimensions + 'px;');
    
      $('.yinstagram-scroller-auto li span em', this).attr( 'style', 'width: ' + AuDimensions + 'px; height: ' + AuDimensions + 'px;');
      
      $('.vert .simply-scroll-list li', this).attr( 'style', 'height:' + AuDimensions + 'px;');
    });
  });
}

function resizeShortcodeImagesInfinite() {
  jQuery(function($){
    $('.yinstagram-shortcode-infinite').each(function() {
      var contentWidthInf = parseInt($('.yinstagram-scroller-infinite', this).width()),
        infDimensions = ( contentWidthInf * 24.9 ) / 100;
      
      $('.yinstagram-scroller-infinite li img', this).attr( 'style', 'width: ' + infDimensions + 'px; height: ' + infDimensions + 'px;');
      
      $('.yinstagram-scroller-infinite li span em', this).attr( 'style', 'width: ' + infDimensions + 'px; height: ' + infDimensions +'px;');
    });
  });
}

function resizeWidgetImages() {
  jQuery(function($){
    $('.yinstagram_grid').each(function() {
      var contentWidthAu = parseInt($(this).width());

      $(this).find('li img').attr( 'style', 'width: '+contentWidthAu+'px; height:'+contentWidthAu+'px;');
    });
  });
}

function resizeWidgetProfile() {
  jQuery(function($){
    $('.yinstagram_profile').each(function() {
      var contentWidthAu = parseInt($('ul.images', this).width()),
        dimensions = ( contentWidthAu * 24.9 ) / 100;

      $('ul.images li img', this).attr( 'style', 'width: '+dimensions+'px; height:'+dimensions+'px;');
    });
  });
}

function loadInfiniteImages(yinstagram_shortcode_images_au_inf, infDimensions, ySinfinite) {
  jQuery(function($){
    var iBreak = parseInt($('.yinstagram-inf-images-i', ySinfinite).val(), 10),
      timeDelayInfS = 0;
      
    $('.yinstagram-load-more').hide();
    
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

    //show load more button after load finished
    timeDelayButton = timeDelayInfS + 512;
    setTimeout( function() {
      $('.yinstagram-load-more').show();
    }, timeDelayButton);

    //remove load more button if no image again
    if ( iBreak > $('.yinstagram-inf-images-i').attr('peak') ) {
      $('.yinstagram-load-more').remove();
    }
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

function getQtipContentSelector(imgSelector, searchvalue, newvalue) {
  var selector = imgSelector;

  return selector ? selector.replace(searchvalue, newvalue) : '';
}

function getInstagramUserLink(userName) {
  return '<a href="http://instagram.com/' + userName + '" target="_blank">' + userName + '</a>';
}

function yinstagramQtip(selector, prefixImgSelector, prefixQtipContentSelector) {
  jQuery(function($){
    $(selector).qtip({
      overwrite: false,
      position: {
        my: 'top center',
        at: 'bottom center'
      },
      hide: {
        fixed: true,
        delay: 250
      },
      content: {
        title: getInstagramUserLink( $(document.getElementsByClassName( getQtipContentSelector( $(selector).attr('class'), prefixImgSelector, prefixQtipContentSelector ) )).attr('username') ),
        text: $(document.getElementsByClassName( getQtipContentSelector( $(selector).attr('class'), prefixImgSelector, prefixQtipContentSelector ) ))
      }
    });
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
