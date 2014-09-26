<div class="wrap">
  <div id="icon-edit" class="icon32 icon32-posts-quote"><br></div>
  <h2>Settings</h2>
  <?php if (!function_exists('curl_version')): ?>
    <div class="error">
      <p>Please enable cURL.</p>
    </div>  
  <?php endif; ?>
  <?php if ($message): ?>
    <div class="<?php echo $message['class']; ?>">
      <p><?php echo $message['msg']; ?></p>
    </div>
  <?php if (isset($message['cookie'])) setcookie('yinstagram_response', null, time()-1, '/'); endif; ?>
  <form action="" method="post" enctype="multipart/form-data">
    <input type="hidden" name="update_settings" value="1" />
    <p>Go to <a href="http://instagram.com/developer" target="_blank">http://instagram.com/developer</a> to register Instagram client, or <a id="yinstagram-setup-help-tab" href="#">see these steps</a> for help. See <a id="yinstagram-shortcode-help-tab" href="#">shortcode documentation</a> to override plugin settings.</p>
    <table class="form-table">
      <tbody>
        <tr valign="top">
          <th scope="row">
            <label for="client_id">Client ID</label>
          </th>
          <td>
            <input type="text" name="client_id" id="client_id" class="regular-text" value="<?php echo isset($data['client_id']) ? $data['client_id'] : null; ?>"/>&nbsp;
            <?php if (isset($data['access_token']) && isset($data['user'])): ?>
              <strong id="connected" style="color: green;" title="<?php echo $data['user']->full_name; ?>">Connected, logged in as <?php echo $data['user']->username; ?>.</strong>
            <?php else: ?>
              <strong style="color: red;">Not Connected.</strong>
            <?php endif; ?>
            <p class="description">Your Instagram Client Id.</p>
          </td>
        </tr>
        <tr valign="top">
          <th scope="row">
            <label for="client_secret">Client Secret</label>
          </th>
          <td>
            <input type="text" name="client_secret" id="client_secret" class="regular-text" value="<?php echo isset($data['client_secret']) ? $data['client_secret'] : null; ?>"/>
            <p class="description">Your Instagram Client Secret.</p>
          </td>
        </tr>
        <tr valign="top">
          <th scope="row">Display Images</th>
          <td>
            <input name="di_radio_previous_value" type="hidden" value="<?php echo $data['display_your_images']; ?>"/>
            <fieldset>
              <legend class="screen-reader-text">
                <span>Display Your Images</span>
              </legend>
              <label title="Get the most recent media published by a user.">
                <input type="radio" value="recent" name="display_images" <?php echo ( $data['display_your_images'] == 'recent' ) ? 'checked="checked"' : null; ?>>
                <span>Recent</span>
              </label>
              <br>
              <label title="See the authenticated user's feed.">
                <input type="radio" value="feed" name="display_images" <?php echo ( ($data['display_your_images'] == 'feed') ) ? 'checked="checked"' : null; ?>>
                <span>Feed</span>
              </label>
              <br>
              <label title="See the authenticated user's list of media they've liked.">
                <input type="radio" value="liked" name="display_images" <?php echo ( ($data['display_your_images'] == 'liked') ) ? 'checked="checked"' : null; ?>>
                <span>Liked</span>
              </label>
              <br>
              <label title="Tags">
                <input type="radio" value="hashtag" name="display_images" <?php echo ( ($data['display_your_images'] == 'hashtag') && $data['display_your_images'] ) ? 'checked="checked"' : null; ?>>
                <span>Tags</span>
              </label>
            </fieldset>
          </td>
        </tr>
        <tr valign="top" id="showUsername" style="<?php echo ( $data['display_your_images'] != 'recent' ) ? 'display: none;' : ''; ?>">
          <th scope="row">
            <label for="username_of_user_id">Username</label>
          </th>
          <td>
            <input type="text" name="username_of_user_id" id="username_of_user_id" class="regular-text" value="<?php echo isset($data['username_of_user_id']) ? $data['username_of_user_id'] : null; ?>" placeholder="<?php echo isset( $data['user']->username ) ? $data['user']->username : null; ?>"/>
            <p class="description">Get the most recent images published by a username. Leave blank to used your username.</p>
          </td>
        </tr>
        <tr valign="top" id="showHashtags" style="<?php echo ($data['option_display_the_following_hashtags']) ? '' : 'display: none;'; ?>">
          <th scope="row">Display The Following Hashtags</th>
          <td>
            <fieldset>
              <legend class="screen-reader-text">
                <span>Display The Following Hashtags</span>
              </legend>
              <label title="Yes" style="display: none;">
                <input type="radio" value="1" name="option_display_the_following_hashtags" <?php echo ($data['option_display_the_following_hashtags'] == '1') ? 'checked="checked"' : null; ?>>
                <span>Yes</span>
              </label>
              <!-- <br> -->
              <label title="No" style="display: none;">
                <input type="radio" value="0" name="option_display_the_following_hashtags" <?php echo ( $data['option_display_the_following_hashtags'] == '0' ) ? 'checked="checked"' : null; ?>>
                <span>No</span>
              </label>
              <div>
                <p>
                  <textarea id="display_the_following_hashtags" class="large-text code" cols="50" rows="10" name="display_the_following_hashtags"><?php echo isset($data['display_the_following_hashtags']) ? $data['display_the_following_hashtags'] : null; ?></textarea>
                </p>
                <p class="description">Hashtags separated by comma, e.g. <code>#buildings, #graffiti, #art</code> etc.</p>
              </div>
            </fieldset>
          </td>
        </tr>
        <tr valign="top">
          <th scope="row">
            <label for="size">Image Size</label>
          </th>
          <td>
            <select id="size" name="size">
              <option value="thumbnail" <?php echo (($data['size'] == 'thumbnail') || !isset($data['size'])) ? 'selected="selected"' : null; ?>>Thumbnail&nbsp;</option>
              <option value="low_resolution" <?php echo ($data['size'] == 'low_resolution') ? 'selected="selected"' : null; ?>>Low Resolution&nbsp;</option>
              <option value="standard_resolution" <?php echo ($data['size'] == 'standard_resolution') ? 'selected="selected"' : null; ?>>Standard Resolution&nbsp;</option>
            </select>
          </td>
        </tr>
        <tr valign="top">
          <th scope="row">
            <label for="size">Number Of Images</label>
          </th>
          <td>
            <select id="size" name="number_of_images">
              <option value="1" <?php echo ($data['number_of_images'] == '1') ? 'selected="selected"' : null; ?>>less than or equal to 80 images&nbsp;</option>
              <option value="2" <?php echo ($data['number_of_images'] == '2') ? 'selected="selected"' : null; ?>>less than or equal to 120 images&nbsp;</option>
              <option value="3" <?php echo ($data['number_of_images'] == '3') ? 'selected="selected"' : null; ?>>less than or equal to 160 images&nbsp;</option>
              <option value="4" <?php echo ($data['number_of_images'] == '4') ? 'selected="selected"' : null; ?>>less than or equal to 180 images&nbsp;</option>
              <option value="5" <?php echo ($data['number_of_images'] == '5') ? 'selected="selected"' : null; ?>>less than or equal to 220 images&nbsp;</option>
              <option value="6" <?php echo ($data['number_of_images'] == '6') ? 'selected="selected"' : null; ?>>less than or equal to 260 images&nbsp;</option>
              <option value="7" <?php echo ($data['number_of_images'] == '7') ? 'selected="selected"' : null; ?>>less than or equal to 280 images&nbsp;</option>
              <option value="8" <?php echo ($data['number_of_images'] == '8') ? 'selected="selected"' : null; ?>>less than or equal to 320 images&nbsp;</option>
              <option value="9" <?php echo ($data['number_of_images'] == '9') ? 'selected="selected"' : null; ?>>less than or equal to 360 images&nbsp;</option>
              <option value="10" <?php echo ($data['number_of_images'] == '10') ? 'selected="selected"' : null; ?>>less than or equal to 380 images&nbsp;</option>
            </select>
          </td>
        </tr>
      </tbody>
    </table>
    <p class="submit">
      <?php if ( isset($data['access_token']) && isset($data['user']) ): ?>
        <input id="submit" class="button-primary" type="submit" value="Save Changes" name="submit">&nbsp;
        <input id="yinstagram-logout" class="button-primary" type="button" value="Logout" name="yinstagram-logout">&nbsp;
      <?php else: ?>
        <input id="submit" class="button-primary" type="submit" value="Save and Connect" name="submit">&nbsp;
      <?php endif; ?>
        <input id="yinstagram-restore-settings" class="button-primary" type="button" value="Reset" name="yinstagram-restore-settings">
    </p>
  </form>
</div>
<div id="dialog-confirm" title="Confirmation" style="display: none;">
  <p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>These will also disconnect from Google API if connected. Are you sure?</p>
</div>
