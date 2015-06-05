<div class="wrap">
  <div id="icon-edit" class="icon32 icon32-posts-quote"><br></div>
  <h2><?php _e('Settings' ,'yakadanda-instagram'); ?></h2>
  <?php if (!function_exists('curl_version')): ?>
    <div class="error">
      <p><?php _e('Please enable cURL.', 'yakadanda-instagram'); ?></p>
    </div>  
  <?php endif; ?>
  <?php if ($message): ?>
    <div id="yinstagram-msg" class="<?php echo $message['class']; ?>">
      <p><?php echo $message['msg']; ?></p>
    </div>
  <?php if (isset($message['cookie'])) setcookie('yinstagram_response', null, time()-1, '/'); endif; ?>
  <form action="" method="post" enctype="multipart/form-data">
    <input type="hidden" name="update_settings" value="1" />
    <p><?php echo sprintf(__('Go to <a href="$s" target="_blank">http://instagram.com/developer</a> to register Instagram client, or <a id="yinstagram-setup-help-tab" href="#">see these steps</a> for help. See <a id="yinstagram-shortcode-help-tab" href="#">shortcode documentation</a> to override plugin settings.', 'yakadanda-instagram'), 'http://instagram.com/developer'); ?></p>
    <table class="form-table">
      <tbody>
        <tr valign="top">
          <th scope="row">
            <label for="client_id"><?php _e('Client ID', 'yakadanda-instagram'); ?></label>
          </th>
          <td>
            <input type="text" name="client_id" id="client_id" class="regular-text" value="<?php echo isset($data['client_id']) ? $data['client_id'] : null; ?>"/>&nbsp;
            <?php if (isset($data['access_token']) && isset($data['user'])): ?>
              <strong id="connected" style="color: green;" title="<?php echo $data['user']->full_name; ?>"><?php echo sprintf(__('Connected, logged in as %s', 'yakadanda-instagram'), $data['user']->username); ?></strong>
            <?php else: ?>
              <strong style="color: red;"><?php _e('Not Connected.', 'yakadanda-instagram'); ?></strong>
            <?php endif; ?>
            <p class="description"><?php _e('Your Instagram Client Id.', 'yakadanda-instagram'); ?></p>
          </td>
        </tr>
        <tr valign="top">
          <th scope="row">
            <label for="client_secret"><?php _e('Client Secret', 'yakadanda-instagram'); ?></label>
          </th>
          <td>
            <input type="text" name="client_secret" id="client_secret" class="regular-text" value="<?php echo isset($data['client_secret']) ? $data['client_secret'] : null; ?>"/>
            <p class="description"><?php _e('Your Instagram Client Secret.', 'yakadanda-instagram'); ?></p>
          </td>
        </tr>
        <tr valign="top">
          <th scope="row"><?php _e('Display Images', 'yakadanda-instagram'); ?></th>
          <td>
            <input name="di_radio_previous_value" type="hidden" value="<?php echo $data['display_your_images']; ?>"/>
            <fieldset>
              <legend class="screen-reader-text">
                <span><?php _e('Display Images', 'yakadanda-instagram'); ?></span>
              </legend>
              <label title="Get the most recent media published by a user.">
                <input type="radio" value="recent" name="display_images" <?php echo ( $data['display_your_images'] == 'recent' ) ? 'checked="checked"' : null; ?>>
                <span><?php _e('Recent', 'yakadanda-instagram'); ?></span>
              </label>
              <br>
              <label title="See the authenticated user's feed.">
                <input type="radio" value="feed" name="display_images" <?php echo ( ($data['display_your_images'] == 'feed') ) ? 'checked="checked"' : null; ?>>
                <span><?php _e('Feed', 'yakadanda-instagram'); ?></span>
              </label>
              <br>
              <label title="See the authenticated user's list of media they've liked.">
                <input type="radio" value="liked" name="display_images" <?php echo ( ($data['display_your_images'] == 'liked') ) ? 'checked="checked"' : null; ?>>
                <span><?php _e('Liked', 'yakadanda-instagram'); ?></span>
              </label>
              <br>
              <label title="Tags">
                <input type="radio" value="hashtag" name="display_images" <?php echo ( ($data['display_your_images'] == 'hashtag') && $data['display_your_images'] ) ? 'checked="checked"' : null; ?>>
                <span><?php _e('Tags', 'yakadanda-instagram'); ?></span>
              </label>
            </fieldset>
          </td>
        </tr>
        <tr valign="top" id="showUsername" style="<?php echo ( $data['display_your_images'] != 'recent' ) ? 'display: none;' : ''; ?>">
          <th scope="row">
            <label for="username_of_user_id"><?php _e('Username', 'yakadanda-instagram'); ?></label>
          </th>
          <td>
            <input type="text" name="username_of_user_id" id="username_of_user_id" class="regular-text" value="<?php echo isset($data['username_of_user_id']) ? $data['username_of_user_id'] : null; ?>" placeholder="<?php echo isset( $data['user']->username ) ? $data['user']->username : null; ?>"/>
            <p class="description"><?php _e('Get the most recent images published by a username. Leave blank to used your username.', 'yakadanda-instagram'); ?></p>
          </td>
        </tr>
        <tr valign="top" id="showHashtags" style="<?php echo ($data['option_display_the_following_hashtags']) ? '' : 'display: none;'; ?>">
          <th scope="row"><?php _e('Hashtags', 'yakadanda-instagram'); ?></th>
          <td>
            <fieldset>
              <legend class="screen-reader-text">
                <span><?php _e('Hashtags', 'yakadanda-instagram'); ?></span>
              </legend>
              <label title="Yes" style="display: none;">
                <input type="radio" value="1" name="option_display_the_following_hashtags" <?php echo ($data['option_display_the_following_hashtags'] == '1') ? 'checked="checked"' : null; ?>>
                <span><?php _e('Yes', 'yakadanda-instagram'); ?></span>
              </label>
              <!-- <br> -->
              <label title="No" style="display: none;">
                <input type="radio" value="0" name="option_display_the_following_hashtags" <?php echo ( $data['option_display_the_following_hashtags'] == '0' ) ? 'checked="checked"' : null; ?>>
                <span><?php _e('No', 'yakadanda-instagram'); ?></span>
              </label>
              <div>
                <p>
                  <textarea id="display_the_following_hashtags" class="large-text code" cols="50" rows="3" name="display_the_following_hashtags"><?php echo isset($data['display_the_following_hashtags']) ? $data['display_the_following_hashtags'] : null; ?></textarea>
                </p>
                <p class="description"><?php echo sprintf(__('Tag separated by comma, e.g. <code>#buildings, #graffiti, #art</code> etc.', 'yakadanda-instagram')); ?></p>
              </div>
            </fieldset>
          </td>
        </tr>
        <tr valign="top">
          <th scope="row">
            <label for="size"><?php _e('Image Size', 'yakadanda-instagram'); ?></label>
          </th>
          <td>
            <select id="size" name="size">
              <option value="thumbnail" <?php echo (($data['size'] == 'thumbnail') || !isset($data['size'])) ? 'selected="selected"' : null; ?>><?php _e('Thumbnail', 'yakadanda-instagram'); ?>&nbsp;</option>
              <option value="low_resolution" <?php echo ($data['size'] == 'low_resolution') ? 'selected="selected"' : null; ?>><?php _e('Low Resolution', 'yakadanda-instagram'); ?>&nbsp;</option>
              <option value="standard_resolution" <?php echo ($data['size'] == 'standard_resolution') ? 'selected="selected"' : null; ?>><?php _e('Standard Resolution', 'yakadanda-instagram'); ?>&nbsp;</option>
            </select>
          </td>
        </tr>
        <tr valign="top">
          <th scope="row">
            <label for="size"><?php _e('Number Of Images', 'yakadanda-instagram'); ?></label>
          </th>
          <td>
            <select id="size" name="number_of_images">
              <option value="1" <?php echo ($data['number_of_images'] == '1') ? 'selected="selected"' : null; ?>><?php _e('less than or equal to 80 images', 'yakadanda-instagram'); ?>&nbsp;</option>
              <option value="2" <?php echo ($data['number_of_images'] == '2') ? 'selected="selected"' : null; ?>><?php _e('less than or equal to 120 images', 'yakadanda-instagram'); ?>&nbsp;</option>
              <option value="3" <?php echo ($data['number_of_images'] == '3') ? 'selected="selected"' : null; ?>><?php _e('less than or equal to 160 images', 'yakadanda-instagram'); ?>&nbsp;</option>
              <option value="4" <?php echo ($data['number_of_images'] == '4') ? 'selected="selected"' : null; ?>><?php _e('less than or equal to 180 images', 'yakadanda-instagram'); ?>&nbsp;</option>
              <option value="5" <?php echo ($data['number_of_images'] == '5') ? 'selected="selected"' : null; ?>><?php _e('less than or equal to 220 images', 'yakadanda-instagram'); ?>&nbsp;</option>
              <option value="6" <?php echo ($data['number_of_images'] == '6') ? 'selected="selected"' : null; ?>><?php _e('less than or equal to 260 images', 'yakadanda-instagram'); ?>&nbsp;</option>
              <option value="7" <?php echo ($data['number_of_images'] == '7') ? 'selected="selected"' : null; ?>><?php _e('less than or equal to 280 images', 'yakadanda-instagram'); ?>&nbsp;</option>
              <option value="8" <?php echo ($data['number_of_images'] == '8') ? 'selected="selected"' : null; ?>><?php _e('less than or equal to 320 images', 'yakadanda-instagram'); ?>&nbsp;</option>
              <option value="9" <?php echo ($data['number_of_images'] == '9') ? 'selected="selected"' : null; ?>><?php _e('less than or equal to 360 images', 'yakadanda-instagram'); ?>&nbsp;</option>
              <option value="10" <?php echo ($data['number_of_images'] == '10') ? 'selected="selected"' : null; ?>><?php _e('less than or equal to 380 images', 'yakadanda-instagram'); ?>&nbsp;</option>
            </select>
          </td>
        </tr>
      </tbody>
    </table>
    <p class="submit">
      <?php if ( isset($data['access_token']) && isset($data['user']) ): ?>
        <input id="submit" class="button-primary" type="submit" value="Save Changes" name="submit">&nbsp;
        <input id="yinstagram-logout" class="button-primary" type="button" value="<?php _e('Logout', 'yakadanda-instagram'); ?>" name="yinstagram-logout">&nbsp;
      <?php else: ?>
        <input id="submit" class="button-primary" type="submit" value="<?php _e('Save and Connect', 'yakadanda-instagram'); ?>" name="submit">&nbsp;
      <?php endif; ?>
        <input id="yinstagram-restore-settings" class="button-primary" type="button" value="<?php _e('Reset', 'yakadanda-instagram'); ?>" name="yinstagram-restore-settings">
    </p>
  </form>
</div>
<div id="dialog-confirm" title="Confirmation" style="display: none;">
  <p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span><?php _e('These will also disconnect from Google API if connected. Are you sure?', 'yakadanda-instagram'); ?></p>
</div>
