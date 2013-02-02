<?php
function yinstagram_shortcode( $atts ) {
  list( $client_id, $client_secret, $display_your_images, $display_the_following_hashtags ) = YISettings::data();
  list( $access_token, $username, $picture, $fullname ) = YISettings::authentication_data();
  list( $logo_display_file, $header_menu_color, $background_color, $resolution, $height, $direction, $speed, $social_links ) = YIDisplayOptions::data();
  
  $pictures = array();
  if ( $display_your_images ) list( $next_url, $pictures[] ) = yinstagram_by_self();
  if ( $display_the_following_hashtags ) list( $next_url, $pictures[] ) = yinstagram_by_tags($next_url);
  
  $data = array();
  if ( $pictures ) {
    foreach ( $pictures as $images ) {
      $data = array_merge( $data, (array)$images );
    }
  }
  
  echo '<input name="next_url" type="hidden" value="' . $next_url . '">';
  
  echo '<input name="height" type="hidden" value="' . $height . '">';
  echo '<input name="direction" type="hidden" value="' . $direction . '">';
  echo '<input name="speed" type="hidden" value="' . $speed . '">';
  
  ?>
  <!-- beginheader-->
  <div class="yi-header" style="background-color: <?php echo $header_menu_color; ?>;" >
    <?php echo ($logo_display_file) ? '<img src="'. $logo_display_file .'" alt="Logo">': null; ?>
  </div>
  <div class="clear"></div>
  <!-- endheader-->
  
  <div id="images-content"><ul id="yakadanda-instagram-images"><?php
  
  $i = 0;
  
  foreach( $data as $datum ) { $i++;
    
    echo ($i==1) ? '<li>' : null;
    
    ?><img class="yi-fluid-img" alt="<?php echo $datum['id']?>" src="<?php echo $datum['images']->thumbnail->url; ?>"><?php
    
    echo ($i==4) ? '</li>' : null;
    $i = ($i==4) ? 0 : $i;
    
  }
  
  echo '</li>';
  
  ?></ul></div>
  
    <!-- beginfooter -->
    <div class="clear"></div>
    <div class="yi-footer" style="background-color: <?php echo $background_color; ?>;">
      <?php if ($social_links): ?>
      <!-- AddThis Button BEGIN -->
      <div class="addthis_toolbox addthis_default_style ">
        <a class="addthis_button_facebook_like" fb:like:layout="button_count"></a>
        <a class="addthis_button_tweet"></a>
        <a class="addthis_button_google_plusone" g:plusone:size="medium"></a>
        <!-- <a class="addthis_button_pinterest_pinit"></a>
        <a class="addthis_counter addthis_pill_style"></a> -->
      </div>
      <script type="text/javascript" src="http://s7.addthis.com/js/300/addthis_widget.js#pubid=xa-50b30c8d0ad640e9"></script>
      <!-- AddThis Button END -->
      <?php endif; ?>
    </div>
    <div class="clear"></div>
    <!-- endfooter -->
  <?php
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

function yinstagram_bottom($social_links) {
  list( $logo_display_file, $header_menu_color, $background_color, $resolution, $height, $direction, $speed ) = YIDisplayOptions::data();
  ?>
    <div class="clear"></div>
    <div class="yi-footer" style="background-color: <?php echo $background_color; ?>;">
      <!-- AddThis Button BEGIN -->
      <div class="addthis_toolbox addthis_default_style ">
        <a class="addthis_button_facebook_like" fb:like:layout="button_count"></a>
        <a class="addthis_button_tweet"></a>
        <a class="addthis_button_google_plusone" g:plusone:size="medium"></a>
        <!-- <a class="addthis_button_pinterest_pinit"></a>
        <a class="addthis_counter addthis_pill_style"></a> -->
      </div>
      <script type="text/javascript" src="http://s7.addthis.com/js/300/addthis_widget.js#pubid=xa-50b30c8d0ad640e9"></script>
      <!-- AddThis Button END -->
    </div>
    <div class="clear"></div>
  <?php
}

function yinstagram_by_self() {
  list( $client_id, $client_secret, $display_your_images, $display_the_following_hashtags ) = YISettings::data();
  list( $access_token, $username, $picture, $fullname ) = YISettings::authentication_data();
  $responses = yinstagram_fetch_data( 'https://api.instagram.com/v1/users/self/media/recent/?access_token=' . $access_token );
  $responses = json_decode( $responses );
  
  $pictures_with_tags = $pictures_without_tags = array();
  if ( $responses ) {
    foreach ( $responses->data as $response ) {
      if ( $display_the_following_hashtags ) {
        $response_tags = $response->tags;
        if ( count( array_intersect( $display_the_following_hashtags, $response_tags ) ) > 0 ) {
          $pictures_with_tags[] = array( 'id' => $response->id, 'images' => $response->images );
        }
      }
      $pictures_without_tags[] = array( 'id' => $response->id, 'images' => $response->images );
    }
  }
  
  if ( $display_the_following_hashtags ) {
    $output = (!empty($pictures_with_tags)) ? $pictures_with_tags : $pictures_without_tags;
  } else {
    $output = $pictures_without_tags;
  }
  
  $next_url = ($responses->pagination->next_url) ? $responses->pagination->next_url : null;
  
  return array( $next_url, $output );
}

function yinstagram_by_tags($next_url=NULL) {
  list( $client_id, $client_secret, $display_your_images, $display_the_following_hashtags ) = YISettings::data();
  list( $access_token, $username, $picture, $fullname ) = YISettings::authentication_data();
  
  $number_of_tags = count( $display_the_following_hashtags );
  $j = round( 18 / $number_of_tags );
  
  $output = array();
  foreach ( $display_the_following_hashtags as $tag ) {
    
    if ($next_url) {
      $responses = yinstagram_fetch_data( $next_url );
    } else {
      $responses = yinstagram_fetch_data( 'https://api.instagram.com/v1/tags/' . $tag . '/media/recent?access_token=' . $access_token );
    }
    
    $responses = json_decode( $responses );
    
    $i = 0;
    
    $pictures = array();
    if ( $responses ) {
      foreach ( $responses->data as $response ) { $i++;
        $pictures[] = array( 'id' => $response->id, 'images' => $response->images );
        if ( $i==$j ) break;
      }
      $next_url = ($responses->pagination->next_url) ? $responses->pagination->next_url : null;
    }
    $output = array_merge($output, $pictures);
  }
  
  return array( $next_url, $output );
}
