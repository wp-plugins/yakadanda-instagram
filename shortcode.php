<?php
add_shortcode('yinstagram', 'yinstagram_shortcode');
function yinstagram_shortcode($atts) {
  global $wp;
  
  $yinstagram = yinstagram_get_options();
  $auth = get_option('yinstagram_access_token');
  $data = null;
  $output = '<p>Not Connected.</p>';
  
  $yinstagram['number_of_images'] = isset($yinstagram['number_of_images']) ? $yinstagram['number_of_images'] : '1';
  $yinstagram['size'] = isset($yinstagram['size']) ? $yinstagram['size'] : 'thumbnail';
  
  if (isset($auth['access_token']) && isset($auth['user'])) {
    $output = '<p>Request timed out, or no have ' . $yinstagram['display_your_images'] . ' images.</p>';
    switch ($yinstagram['display_your_images']) {
      case 'hashtag':
        $data = yinstagram_get_tags_images($auth, $yinstagram['display_the_following_hashtags'], $yinstagram['number_of_images']);
        break;
      default:
        $data = yinstagram_get_own_images($auth, $yinstagram['display_your_images'], $yinstagram['number_of_images'], $yinstagram['username_of_user_id'], true);
    }
    if ($yinstagram['order'] == 'shuffle') { shuffle($data); }
  }
  
  if (!empty($data)) {
    $is_inifinte = false;
    switch( $yinstagram['scroll'] ) {
      case 'infinite':
        $is_inifinte = true;
        $output = yinstagram_get_scroll_infinite($yinstagram, $data);
        break;
      default:
        yinstagram_styles($yinstagram);
        $output = yinstagram_get_scroll_auto($yinstagram, $data);
    }
    if ($yinstagram['display_social_links']) {
      $current_url = home_url(add_query_arg(array(),$wp->request));
      
      $socialClass = ($is_inifinte) ? 'yinstagram-social infinite_scroll' : 'yinstagram-social';
      
      $output .= '<div class="' . $socialClass . '"><ul class="links">';
      $output .= '<li><iframe src="//www.facebook.com/plugins/like.php?href='.urlencode($current_url).'&amp;width&amp;layout=button_count&amp;action=like&amp;show_faces=false&amp;share=false&amp;height=21&amp;appId=561399377290377" scrolling="no" frameborder="0" style="border:none; overflow:hidden; height:21px;" allowTransparency="true"></iframe></li>';
      $output .= '<li><a href="https://twitter.com/share" class="twitter-share-button" data-lang="en">Tweet</a></li>';
      $output .= '<li><div class="g-plusone" data-size="medium"></div></li>';
      $output .= '</ul></div>';
    }
  }
  
  return $output;
}

function yinstagram_get_scroll_auto($yinstagram, $data) {
  $i = $j = 0;
  $images = array();
  $limit = yinstagram_number_of_images( $yinstagram );
  
  $output = '<input id="yinstagram-shortcode-settings-au" name="yinstagram-shortcode-settings-au" type="hidden" value="' . htmlentities( json_encode( array( 'frame_rate' => $yinstagram['frame_rate'], 'speed' => $yinstagram['speed'], 'direction' => $yinstagram['direction'] ) ) ) . '">';
  
  $output .= '<ul id="yinstagram-scroller-auto">';
  
  foreach ( $data as $datum ) {
    if ( $datum->type != 'image' ) continue;
    $i++; $j++;
    $img_src = $datum->images->thumbnail->url;
    if ($yinstagram['size'] == 'low_resolution')
      $img_src = $datum->images->low_resolution->url;
    elseif ($yinstagram['size'] == 'standard_resolution')
      $img_src = $datum->images->standard_resolution->url;
    
    $output .= ($i == 1) ? '<li>' : null;
    
    $output .= '<span class="load_as-' . $datum->id . '"></span>';
    
    $output .= ($i == 4) ? '</li>' : null;
    $i = ($i == 4) ? 0 : $i;
    
    $images[] = array(
        'id' => $datum->id,
        'title' => str_replace('"', "'", (string) $datum->caption->text),
        'src' => $img_src
      );
    
    if ($j == $limit) break;
  }
  
  if ($j != $limit) $output .= '</li>';
  
  $output .= '</ul>';
  
  if ( $yinstagram['direction'] == 'backwards' ) $images = array_reverse( $images );
  
  $output .= '<textarea id="yinstagram-shortcode-images-auto" name="yinstagram-shortcode-images-auto" style="display: none;">' . json_encode($images) . '</textarea>';
  
  return $output;
}

function yinstagram_get_scroll_infinite($yinstagram, $data) {
  $i = $j = $k = 0;
  $images = array();
  $limit = yinstagram_number_of_images( $yinstagram );
  
  $output = '<input id="yinstagram-shortcode-settings-inf" name="yinstagram-shortcode-settings-inf" type="hidden" value="' . htmlentities( json_encode( array( 'colorbox_status' => $yinstagram['colorbox'], 'colorbox_theme' => $yinstagram['theme'], 'colorbox_effect' => $yinstagram['effect'] ) ) ) . '">';
  
  $output .= '<input id="yinstagram-inf-images-i" name="yinstagram-inf-images-i" type="hidden" value="15">';
  
  $output .= '<ul id="yinstagram-scroller-infinite">';
  
  foreach ( $data as $datum ) {
    if ( $datum->type != 'image' ) continue;
    $i++; $j++;
    $img_src = $datum->images->thumbnail->url;
    if ($yinstagram['size'] == 'low_resolution')
      $img_src = $datum->images->low_resolution->url;
    elseif ($yinstagram['size'] == 'standard_resolution')
      $img_src = $datum->images->standard_resolution->url;
    
    $output .= ($i == 1) ? '<li>' : null;
    
    if ($yinstagram['colorbox']) $output .= '<a class="yinstagram-cbox" style="cursor: pointer;" href="' . $datum->images->standard_resolution->url . '" title="' . yinstagram_get_excerpt(str_replace('"', "'", (string) $datum->caption->text)) . '">';
    
    //$output .= '<span class="load_is-' . $datum->id . '"></span>';
    $output .= '<span class="load_is-' . $k++ . '"></span>';
    
    if ($yinstagram['colorbox']) $output .= '</a>';
    
    $output .= ($i == 4) ? '</li>' : null;
    $i = ($i == 4) ? 0 : $i;
    
    $images[] = array(
        'id' => $datum->id,
        'title' => str_replace('"', "'", (string) $datum->caption->text),
        'src' => $img_src,
        'tags' => $datum->tags,
        'link' => $datum->link
      );
    
    if ($j == $limit) break;
  }
  
  if ($j != $limit) $output .= '</li>';
  
  $output .= '</ul>';
  
  $output .= '<textarea id="yinstagram-shortcode-images-infinite" name="yinstagram-shortcode-images-infinite" style="display: none;">' . json_encode($images) . '</textarea>';
  
  return $output;
}

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

function yinstagram_extract_hashtags($data) {
  // remove # character and space character
  $output = str_replace("#", "", $data);
  $output = str_replace(" ", "", $output);

  return explode(',', $output);
}

function yinstagram_get_own_images($auth, $display_images, $number_of_images, $username, $is_shortcode) {
  $responses = null;
  
  switch ($display_images) {
    case 'feed':
      //https://api.instagram.com/v1/users/self/feed?access_token=ACCESS-TOKEN
      $responses = yinstagram_fetch_data('https://api.instagram.com/v1/users/self/feed/?access_token=' . $auth['access_token'] . '&count=33');
      break;
    case 'liked':
      //https://api.instagram.com/v1/users/self/media/liked?access_token=ACCESS-TOKEN
      $responses = yinstagram_fetch_data('https://api.instagram.com/v1/users/self/media/liked/?access_token=' . $auth['access_token'] . '&count=33');
      break;
    default:
      $username = ($username) ? $username: 'self';
      switch ($username) {
        case 'self':
          //https://api.instagram.com/v1/users/3/media/recent/?access_token=ACCESS-TOKEN
          $responses = yinstagram_fetch_data('https://api.instagram.com/v1/users/self/media/recent/?access_token=' . $auth['access_token'] . '&count=33');
          break;
        default:
          $user_id = yinstagram_get_user_id($auth, $username);
          if ($user_id)
            $responses = yinstagram_fetch_data('https://api.instagram.com/v1/users/' . $user_id . '/media/recent/?access_token=' . $auth['access_token'] . '&count=33');
      }
  }
  
  $responses = json_decode($responses);
  
  $output = array();
  
  if ( isset($responses->data) ) {
    $output = $responses->data;

    $next_url = ( isset($responses->pagination->next_url) ) ? $responses->pagination->next_url : null;

    if ($is_shortcode) {
      $i = 0;
      
      while ($next_url) {
        $responses = yinstagram_fetch_data($next_url);
        $responses = json_decode($responses);
        
        if ( isset($responses->data) ) {
          $output = array_merge($output, $responses->data);

          if ($i == $number_of_images )
            break;
          $i++;
          $next_url = ($responses->pagination->next_url) ? $responses->pagination->next_url : null;
        }
      }
    }
  }

  return $output;
}

function yinstagram_get_tags_images($auth, $hashtags, $number_of_images = 1, $is_shortcode = true) {
  $tags = yinstagram_extract_hashtags($hashtags);
  $number_of_hashtags = count($tags);
  $count = round(33 / $number_of_hashtags);
  $output = array();
  
  foreach ($tags as $tag) {
    $responses = yinstagram_fetch_data('https://api.instagram.com/v1/tags/' . $tag . '/media/recent?access_token=' . $auth['access_token'] . '&count=' . $count);
    
    $responses = json_decode($responses);
    
    if ( isset($responses->data) ) {
      $output = array_merge( $output, $responses->data );
      
      $next_url = ( isset($responses->pagination->next_url) ) ? $responses->pagination->next_url : null;
      
      if ($is_shortcode) {
        $i = 0;
        
        while ($next_url) {
          $responses = yinstagram_fetch_data($next_url);
          $responses = json_decode($responses);
          
          if ( isset($responses->data) ) {
            $output = array_merge($output, $responses->data);
            
            if ($i == $number_of_images)
              break;
            $i++;
            $next_url = ($responses->pagination->next_url) ? $responses->pagination->next_url : null;
          }
        }
      }
    }
  }
  
  return yinstagram_shuffle_assoc( $output );
}

function yinstagram_styles($yinstagram) {
  ?>
  <style type="text/css">
    .vert, .vert .simply-scroll-clip {
      height: <?php echo $yinstagram['height'] . 'px'; ?>;
    }
  </style>
  <?php
}

function yinstagram_shuffle_assoc($list) {
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

function yinstagram_number_of_images($data) {
  $loop = isset($data['number_of_images']) ? $data['number_of_images'] : '1';
  
  $output = 80;
  
  if ( $loop == '2' ) $output = 120;
  elseif ( $loop == '3' ) $output = 160;
  elseif ( $loop == '4' ) $output = 180;
  elseif ( $loop == '5' ) $output = 220;
  elseif ( $loop == '6' ) $output = 260;
  elseif ( $loop == '7' ) $output = 280;
  elseif ( $loop == '8' ) $output = 320;
  elseif ( $loop == '9' ) $output = 360;
  elseif ( $loop == '10' ) $output = 380;
  
  return $output;
}
