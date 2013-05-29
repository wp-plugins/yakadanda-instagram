<?php
function yinstagram_shortcode( $atts ) {
  $display_options = (get_option('yinstagram_display_options')) ? get_option('yinstagram_display_options') : array (
        'height' => 300,
        'frame_rate' => 24,
        'speed' => 1,
        'direction' => 'forwards',
        'size' => 'thumbnail',
        'display_social_links' => 0
      );
  
  $yinstagram = array_merge( (array)get_option('yinstagram_settings'), (array)$display_options );
  $auth = get_option('yinstagram_access_token');
  $output = null;
  
  if ($auth) {
    if ( $yinstagram['display_your_images'] ) {
      $data = yinstagram_get_own_images( $yinstagram, $auth );
    } else {
      $data = yinstagram_get_tags_images( $yinstagram, $auth );
    }
    
    if ( ! empty($data) ) {
      yinstagram_styles( $yinstagram );
      $output .= '<input name="frame_rate" type="hidden" value="' . $yinstagram['frame_rate'] . '">';
      $output .= '<input name="speed" type="hidden" value="' . $yinstagram['speed'] . '">';
      $output .= '<input name="direction" type="hidden" value="' . $yinstagram['direction'] . '">';
      $output .= '<input name="height" type="hidden" value="' . $yinstagram['height'] . '">';
      
      $output .= '<ul id="yinstagram-scroller">';
      
      $i = $j = 0;
      
      foreach( $data as $datum ) { $i++; $j++;
        $img_src = $datum->images->thumbnail->url;
        if ( $yinstagram['size'] == 'low_resolution' ) $img_src = $datum->images->low_resolution->url;
        elseif ( $yinstagram['size'] == 'standard_resolution' ) $img_src = $datum->images->standard_resolution->url;
        
        $output .= ($i==1) ? '<li>' : null;
        
        $output .= '<img title="' . str_replace('"', "'", $datum->caption->text) . '" src="' . $img_src . '">';
        
        $output .= ($i==4) ? '</li>' : null;
        $i = ($i==4) ? 0 : $i;
        if ($j==80) break;
      }
      
      $output .= '</li>';
      
      $output .= '</ul>';

      if ( $yinstagram['display_social_links'] == '1' ) {
        $output .= '<!-- AddThis Button BEGIN -->';
        $output .= '<div class="addthis_toolbox addthis_default_style ">';
        $output .= '<a class="addthis_button_facebook_like" fb:like:layout="button_count"></a>';
        $output .= '<a class="addthis_button_tweet"></a>';
        $output .= '<a class="addthis_button_google_plusone" g:plusone:size="medium"></a>';
        $output .= '<!-- <a class="addthis_button_pinterest_pinit"></a>';
        $output .= '<a class="addthis_counter addthis_pill_style"></a> -->';
        $output .= '</div>';
        $output .= '<script type="text/javascript" src="http://s7.addthis.com/js/300/addthis_widget.js#pubid=xa-50b30c8d0ad640e9"></script>';
        $output .= '<!-- AddThis Button END -->';
      }
    } else { $output .= '<p>No have ' . $yinstagram['display_your_images'] . ' images.</p>'; }
  } else {
    $output .= '<p>Not Connected.</p>';
  }
  
  return $output;
}
add_shortcode( 'yinstagram', 'yinstagram_shortcode' );

function yinstagram_fetch_data($url) {
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_TIMEOUT, 20);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  $result = curl_exec($ch);
  curl_close($ch);
  return $result;
}

function yinstagram_extract_hastags( $data ) {
  // remove # character and space character
  $output = str_replace("#", "", $data);
  $output = str_replace(" ", "", $output);
  
  return explode(',', $output);
}

function yinstagram_get_own_images( $yinstagram, $auth, $shortcode = true ) {
  if ( $yinstagram['display_your_images'] == 'feed' ) {
    //https://api.instagram.com/v1/users/self/feed?access_token=ACCESS-TOKEN
    $responses = yinstagram_fetch_data( 'https://api.instagram.com/v1/users/self/feed/?access_token=' . $auth['access_token'] . '&count=33' );
  } elseif ( $yinstagram['display_your_images'] == 'liked' ) {
    //https://api.instagram.com/v1/users/self/media/liked?access_token=ACCESS-TOKEN
    $responses = yinstagram_fetch_data( 'https://api.instagram.com/v1/users/self/media/liked/?access_token=' . $auth['access_token'] . '&count=33' );
  } else {
    //https://api.instagram.com/v1/users/3/media/recent/?access_token=ACCESS-TOKEN
    $responses = yinstagram_fetch_data( 'https://api.instagram.com/v1/users/self/media/recent/?access_token=' . $auth['access_token'] . '&count=33' );
  }
  
  $responses = json_decode( $responses );
  $output = $responses->data;
  
  $next_url = ( isset($responses->pagination->next_url) ) ? $responses->pagination->next_url : null;
  
  if ( $shortcode ) {
    $i = 0;
    while ( $next_url ) {
      $responses = yinstagram_fetch_data( $next_url );
      $responses = json_decode( $responses );

      $output = array_merge( $output, $responses->data );

      if ($i == 1) break;
      $i++;
      $next_url = ($responses->pagination->next_url) ? $responses->pagination->next_url : null;
    }
  }
  
  return $output;
}

function yinstagram_get_tags_images( $yinstagram, $auth, $shortcode = true ) {
  $tags = yinstagram_extract_hastags( $yinstagram['display_the_following_hashtags'] );
  $number_of_tags = count( $tags );
  $count = round( 33 / $number_of_tags );
  $output = array();
  
  foreach ( $tags as $tag ) {
    $responses = yinstagram_fetch_data( 'https://api.instagram.com/v1/tags/' . $tag . '/media/recent?access_token=' . $auth['access_token'] . '&count=' . $count );
    
    $responses = json_decode( $responses );
    $output = array_merge( $output, $responses->data );
    
    $next_url = ( isset($responses->pagination->next_url) ) ? $responses->pagination->next_url : null;
    
    if ( $shortcode ) {
      $i = 0;
      while ( $next_url ) {
        $responses = yinstagram_fetch_data( $next_url );
        $responses = json_decode( $responses );
        
        $output = array_merge( $output, $responses->data );
        
        if ($i == 1) break;
        $i++;
        $next_url = ($responses->pagination->next_url) ? $responses->pagination->next_url : null;
      }
    }
  }
  
  return yinstagram_shuffle_assoc( $output );
}

function yinstagram_styles( $yinstagram ) {
  ?>
    <style type="text/css">
      .vert, .vert .simply-scroll-clip {
        height: <?php echo $yinstagram['height'] . 'px'; ?>;
      }
    </style>
  <?php
}

function yinstagram_shuffle_assoc( $list ) {
  if (!is_array($list))
    return $list;

  $keys = array_keys($list);
  shuffle($keys);
  $random = array();
  foreach ($keys as $key) {
    $random[] = $list[$key];
  }
  return $random;
}
