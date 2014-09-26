<?php
add_shortcode('yinstagram', 'yinstagram_shortcode');
function yinstagram_shortcode($atts) {
  global $wp, $yinstagram_options;

  $a = shortcode_atts( array(
      'display_images' => null, //display_your_images - recent, feed, liked, hashtag
      'username' => null, //username_of_user_id
      'hashtags' => null //display_the_following_hashtags
    ), $atts);

  // Enqueue scripts
  yinstagram_wp_enqueue_scripts_load($yinstagram_options);

  $display_your_images = empty($a['display_images']) ? $yinstagram_options['display_your_images'] : $a['display_images'];
  
  if ( !empty($a['hashtags']) ) { $display_your_images = 'hashtag'; }
  
  $username_of_user_id = $yinstagram_options['username_of_user_id'];
  if ( !empty($a['username']) ) {
    $display_your_images = 'recent';
    $username_of_user_id = $a['username'];
  }
  
  $data = null;
  $output = '<p>Not Connected.</p>';
  
  $yinstagram_options['number_of_images'] = isset($yinstagram_options['number_of_images']) ? $yinstagram_options['number_of_images'] : '1';
  $yinstagram_options['size'] = isset($yinstagram_options['size']) ? $yinstagram_options['size'] : 'thumbnail';
  
  if (isset($yinstagram_options['access_token']) && isset($yinstagram_options['user'])) {
    $output = '<p>Request timed out, or no have ' . $display_your_images . ' images.</p>';
    switch ($display_your_images) {
      case 'hashtag':
        $data = yinstagram_get_tags_images($yinstagram_options['access_token'], $yinstagram_options['display_the_following_hashtags'], $yinstagram_options['number_of_images'], true, $a['hashtags']);
        break;
      default:
        $data = yinstagram_get_own_images($yinstagram_options['access_token'], $display_your_images, $yinstagram_options['number_of_images'], $username_of_user_id, true, $a);
    }
    if ($yinstagram_options['order'] == 'shuffle') { shuffle($data); }
  }
  
  if (!empty($data)) {
    $is_inifinte = false;
    switch( $yinstagram_options['scroll'] ) {
      case 'infinite':
        $is_inifinte = true;
        $output = yinstagram_get_scroll_infinite($yinstagram_options, $data);
        break;
      default:
        yinstagram_styles($yinstagram_options['height']);
        $output = yinstagram_get_scroll_auto($yinstagram_options, $data);
    }
    if ($yinstagram_options['display_social_links']) {
      $current_url = home_url(add_query_arg(array(),$wp->request));
      
      $socialClass = ($is_inifinte) ? 'yinstagram-social infinite_scroll' : 'yinstagram-social';
      
      $output .= '<div class="' . $socialClass . ' clearfix"><ul class="links">';
      $output .= '<li><iframe src="//www.facebook.com/plugins/like.php?href='.urlencode($current_url).'&amp;width&amp;layout=button_count&amp;action=like&amp;show_faces=false&amp;share=false&amp;height=21&amp;appId=561399377290377" scrolling="no" frameborder="0" style="border:none; overflow:hidden; height:21px;" allowTransparency="true"></iframe></li>';
      $output .= '<li><a href="https://twitter.com/share" class="twitter-share-button" data-lang="en">Tweet</a></li>';
      $output .= '<li><div class="g-plusone" data-size="medium"></div></li>';
      $output .= '</ul></div>';
    }
  }
  
  return $output;
}

function yinstagram_get_scroll_auto($yinstagram_options, $data) {
  $i = $j = 0;
  $images = array();
  $limit = yinstagram_get_number_of_images($yinstagram_options['number_of_images']);

  $qtipcontent = null;
  
  $output = '<div class="yinstagram-shortcode-auto">';
  
  $output .= '<ul class="yinstagram-scroller-auto">';
  
  foreach ( $data as $datum ) {
    if ( $datum->type != 'image' ) continue;
    $i++; $j++;
    $img_src = $datum->images->thumbnail->url;
    if ($yinstagram_options['size'] == 'low_resolution')
      $img_src = $datum->images->low_resolution->url;
    elseif ($yinstagram_options['size'] == 'standard_resolution')
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

    if ($yinstagram_options['tooltip'] == 'on')
      $qtipcontent .= '<div class="qtip_as-' . $datum->id . ' yinstagram-qtip-content" style="display: none;" username="' . $datum->user->username . '">' . yinstagram_get_qtip_content($datum) . '</div>';
    
    if ($j == $limit) break;
  }
  
  if ($j != $limit) $output .= '</li>';
  
  $output .= '</ul>';

  $output .= '<input class="yinstagram-shortcode-settings-au" name="yinstagram-shortcode-settings-au" type="hidden" value="' . htmlentities( json_encode( array( 'frame_rate' => $yinstagram_options['frame_rate'], 'speed' => $yinstagram_options['speed'], 'direction' => $yinstagram_options['direction'] ) ) ) . '">';
  
  if ( $yinstagram_options['direction'] == 'backwards' ) $images = array_reverse( $images );
  
  $output .= '<textarea class="yinstagram-shortcode-images-auto" name="yinstagram-shortcode-images-auto" style="display: none;">' . json_encode($images) . '</textarea>';
  
  $output .= '</div>';

  if ($yinstagram_options['tooltip'] == 'on') $output .= $qtipcontent;
  
  return $output;
}

function yinstagram_get_scroll_infinite($yinstagram_options, $data) {
  $i = $j = $k = 0;
  $images = array();
  $limit = yinstagram_get_number_of_images($yinstagram_options['number_of_images']);
  
  if ($yinstagram_options['lightbox'] == 'thickbox') { add_thickbox(); }

  $qtipcontent = null;
  
  $output = '<div class="vert yinstagram-shortcode-infinite">';
  
  $output .= '<ul class="yinstagram-scroller-infinite clearfix">';
  
  foreach ( $data as $datum ) {
    if ( $datum->type != 'image' ) { continue; }
    $i++; $j++;
    $img_src = $datum->images->thumbnail->url;
    if ($yinstagram_options['size'] == 'low_resolution')
      $img_src = $datum->images->low_resolution->url;
    elseif ($yinstagram_options['size'] == 'standard_resolution')
      $img_src = $datum->images->standard_resolution->url;
    
    $output .= ($i == 1) ? '<li>' : null;
    
    switch($yinstagram_options['lightbox']) {
      case 'thickbox':
        $output .= '<a class="yinstagram-lbox thickbox" style="cursor: pointer;" href="' . $datum->images->standard_resolution->url . '?TB_iframe=true" rel="gallery-yinstagram">';
        break;
      case 'colorbox':
        $output .= '<a class="yinstagram-lbox" style="cursor: pointer;" href="' . $datum->images->standard_resolution->url . '">';
        break;
      default:
        $output .= '<a target="_blank" href="' . $datum->images->standard_resolution->url . '">';
    }
    
    $output .= '<span class="load_is-' . $datum->id . '"></span>';
    
    $output .= '</a>';
    
    $output .= ($i == 4) ? '</li>' : null;
    $i = ($i == 4) ? 0 : $i;
    
    $images[] = array(
        'id' => $datum->id,
        'title' => str_replace('"', "'", (string) $datum->caption->text),
        'src' => $img_src,
        'tags' => $datum->tags,
        'link' => $datum->link
      );

    if ($yinstagram_options['tooltip'] == 'on')
      $qtipcontent .= '<div class="qtip_is-' . $datum->id . ' yinstagram-qtip-content" style="display: none;" username="' . $datum->user->username . '">' . yinstagram_get_qtip_content($datum) . '</div>';
    
    if ($j == $limit) break;
  }
  
  if ($j != $limit) $output .= '</li>';
  
  $output .= '</ul>';

  $output .= '<input class="yinstagram-shortcode-settings-inf" name="yinstagram-shortcode-settings-inf" type="hidden" value="' . htmlentities( json_encode( array( 'lightbox' => $yinstagram_options['lightbox'], 'colorbox_theme' => $yinstagram_options['theme'], 'colorbox_effect' => $yinstagram_options['effect'] ) ) ) . '">';
  
  $output .= '<input class="yinstagram-inf-images-i" name="yinstagram-inf-images-i" type="hidden" value="15" peak="' . count($images) . '">';
  
  $output .= '<textarea class="yinstagram-shortcode-images-infinite" name="yinstagram-shortcode-images-infinite" style="display: none;">' . json_encode($images) . '</textarea>';
  
  $output .= '</div>';
  
  $style = ($yinstagram_options['display_social_links']) ? null : ' style="margin-bottom: 1.5em;"';
  
  $output .= '<a href="#" class="yinstagram-load-more"' . $style . '>Load More</a>';

  if ($yinstagram_options['tooltip'] == 'on') $output .= $qtipcontent;
  
  return $output;
}

function yinstagram_extract_hashtags($data) {
  // remove # character and space character
  $output = str_replace("#", "", $data);
  $output = str_replace(" ", "", $output);

  return explode(',', $output);
}

function yinstagram_get_own_images($access_token, $display_images, $number_of_images, $username, $is_shortcode, $a = null) {
  $responses = null;
  
  switch ($display_images) {
    case 'feed':
      //https://api.instagram.com/v1/users/self/feed?access_token=ACCESS-TOKEN
      $responses = yinstagram_fetch_data('https://api.instagram.com/v1/users/self/feed/?access_token=' . $access_token . '&count=33');
      break;
    case 'liked':
      //https://api.instagram.com/v1/users/self/media/liked?access_token=ACCESS-TOKEN
      $responses = yinstagram_fetch_data('https://api.instagram.com/v1/users/self/media/liked/?access_token=' . $access_token . '&count=33');
      break;
    default:
      $username = ($username) ? $username: 'self';
      switch ($username) {
        case 'self':
          //https://api.instagram.com/v1/users/3/media/recent/?access_token=ACCESS-TOKEN
          $responses = yinstagram_fetch_data('https://api.instagram.com/v1/users/self/media/recent/?access_token=' . $access_token . '&count=33');
          break;
        default:
          $user_id = yinstagram_get_user_id($access_token, $username);
          if ($user_id)
            $responses = yinstagram_fetch_data('https://api.instagram.com/v1/users/' . $user_id . '/media/recent/?access_token=' . $access_token . '&count=33');
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

          if ($i == $number_of_images ) break;
          
          $i++;
          $next_url = ($responses->pagination->next_url) ? $responses->pagination->next_url : null;
        }
      }
    }
  }

  return $output;
}

function yinstagram_get_tags_images($access_token, $hashtags, $number_of_images = 1, $is_shortcode = true, $attr_hashtags = null) {
  $tags = empty($attr_hashtags) ? yinstagram_extract_hashtags($hashtags) : yinstagram_extract_hashtags($attr_hashtags);
  
  $number_of_hashtags = count($tags);
  $count = round(33 / $number_of_hashtags);
  $output = array();
  
  foreach ($tags as $tag) {
    $responses = yinstagram_fetch_data('https://api.instagram.com/v1/tags/' . $tag . '/media/recent?access_token=' . $access_token . '&count=' . $count);
    
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

function yinstagram_styles($height) {
  ?>
  <style type="text/css">
    .vert, .vert .simply-scroll-clip {
      height: <?php echo $height . 'px'; ?>;
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

function yinstagram_get_number_of_images($loop) {
  switch($loop) {
    case '2': $output = 120; break;
    case '3': $output = 160; break;
    case '4': $output = 180; break;
    case '5': $output = 220; break;
    case '6': $output = 260; break;
    case '7': $output = 280; break;
    case '8': $output = 320; break;
    case '9': $output = 360; break;
    case '10': $output = 380; break;
    default: $output = 80;
  }
  
  return $output;
}
