jQuery(function($){
  
  $('input[name*="option_display_the_following_hashtags"]').click(function() {
    var showHashtags = $(this).val();
    if (showHashtags == 1) {
      $('#showHashtags').attr('style', 'display: block;');
    } else {
      $('#showHashtags').attr('style', 'display: none;');
    }
  });
  
  if ( $('.wrap').find('input[name$="header_menu_color"]').length == 1 ) {
    
    var f = $.farbtastic('#picker');
    var p = $('#picker').css('opacity', 0.25);
    var selected;
    $('.colorwell')
    .each(function () {
      f.linkTo(this);
      $(this).css('opacity', 0.75);
    })
    .focus(function() {
      if (selected) {
        $(selected).css('opacity', 0.75).removeClass('colorwell-selected');
      }
      f.linkTo(this);
      p.css('opacity', 1);
      $(selected = this).css('opacity', 1).addClass('colorwell-selected');
    });
    
  }
  
  if ( $('body').find('#yakadanda-instagram-images').length == 1 ) {
    
    //Triggers when document first loads 
    imageResize();
    
    //Adjusts image when browser resized
    $(window).bind("resize", function(){
      imageResize();
    });
    
    var next_url = $('input[name$="next_url"]').val();
    
    pullInstagramImages(next_url,1);
    
  }
  
});

function imageResize() {
  jQuery(function($){
    var contentwidth = parseInt($('#images-content').width()),
      dimensions = ( contentwidth * 25 ) / 100;
      
    $('#yakadanda-instagram-images li img').attr( 'style', 'width: '+dimensions+'px; height:'+dimensions+'px;');
    /*$('#yakadanda-instagram-images li').css({
      'height' : dimensions+'px'
    });*/
    
  });
}

function pullInstagramImages(next_url,page_limit) {
  jQuery(function($){
    
    $.ajax({
      type: "GET",
      dataType: "jsonp",
      cache: false,
      url: next_url,
      success: function(response) {
        var contentwidth = $('#images-content').width()
          dimensions = ( contentwidth * 25 ) / 100,
          next_url = response.pagination.next_url,
          data = response.data,
          images = '',
          j = 0,
          page_limit = page_limit+1,
          countImages = $('#yakadanda-instagram-images li:last-child').children().length;
        
        for(i=0; i<data.length; i++) {
          
          if (countImages<4) {
            countImages = countImages + 1;
            images = images + '<img class="yi-fluid-img" style="width: ' + dimensions + 'px; height: ' + dimensions +'px" alt="' + data[i].id + '" src="' + data[i].images.thumbnail['url'] + '">';
            
            if (countImages==4) {
              $('#yakadanda-instagram-images li:last-child').append(images);
              images = '';
            }
          } else {
            ++j;
            images = images + '<img class="yi-fluid-img" style="width: ' + dimensions + 'px; height: ' + dimensions +'px" alt="' + data[i].id + '" src="' + data[i].images.thumbnail['url'] + '">';

            if ( (j==4) || (j==8) || (j==12) || (j==16) || (j==18) ) {
              $('#yakadanda-instagram-images').append('<li>'+images+'</li>');
              images = '';
            }
          } //endOfCountImages
        } //endFor
        
        if ( (typeof next_url != 'undefined') && (page_limit<=6) ) {
          pullInstagramImages(next_url,page_limit);
        } else {
          
          var speed = parseInt($('input[name$="speed"]').val()),
            course = $('input[name$="direction"]').val(),
            tall = parseInt($('input[name$="height"]').val()),
            number_of_list = $("#yakadanda-instagram-images li").size(),
            the_items = Math.floor(number_of_list/3),
            alt_tall = the_items * 100;
            
          if ( tall >= alt_tall ) {
            tall = alt_tall + 100;
          }
          console.log(the_items);
          $('#yakadanda-instagram-images').carouFredSel({
            responsif: true,
            direction: course,
            width: '100%',
            height: tall,
            padding: 0,
            items: the_items,
            auto: {
              play: true,
              timeoutDuration: 0,
              delay: 0,
              duration: speed,
              fx: 'scroll'
            }
          });
          
        }
      }, //endOfSuccess
      error: function(jqXHR, textStatus, errorThrown) {
        alert("You need more images, minimal 19 images");
      } //endOfError
    }); //endOfAjax
    
  });
}
