<div class="wrap">
  <div id="icon-edit" class="icon32 icon32-posts-quote"><br></div>
  <h2>Settings</h2>
  <?php if (isset($_GET['msg'])): ?>
    <div class="error">
      <p><?php echo $_GET['msg']; ?></p>
    </div>
  <?php endif; ?>
  <form action="<?php echo YINSTAGRAM_PLUGIN_URL . '/admin/posteddata-settings.php'; ?>" method="post" enctype="multipart/form-data">
    <p>Go to <a href="http://instagram.com/developer" target="_blank">http://instagram.com/developer</a> to register Instagram client, or <a id="yinstagram-help-tab" href="#">see these manual</a> for help.</p>
    <table class="form-table">
      <tbody>
        <tr valign="top">
          <th scope="row">
            <label for="client_id">Client ID</label>
          </th>
          <td>
            <input type="text" name="client_id" id="client_id" class="regular-text" value="<?php echo isset($data['client_id']) ? $data['client_id'] : null; ?>"/>&nbsp;
            <?php if (isset($data['access_token']) && isset($data['user'])): ?>
              <strong style="color: green;" title="<?php echo $data['full_name']; ?>">Connected, logged in as <?php echo $data['user']->username; ?>.</strong>
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
          <th scope="row">Display Your Images</th>
          <td>
            <input name="dyi_radio_previous_value" type="hidden" value="<?php echo ($display_your_images) ? $display_your_images : 'recent'; ?>"/>
            <fieldset>
              <legend class="screen-reader-text">
                <span>Display Your Images</span>
              </legend>
              <label title="Get the most recent media published by a user.">
                <input type="radio" value="recent" name="display_your_images" <?php echo ( ($display_your_images == 'recent') || !$display_your_images ) ? 'checked="checked"' : null; ?>>
                <span>Recent</span>
              </label>
              <br>
              <label title="See the authenticated user's feed.">
                <input type="radio" value="feed" name="display_your_images" <?php echo ( ($display_your_images == 'feed') ) ? 'checked="checked"' : null; ?>>
                <span>Feed</span>
              </label>
              <br>
              <label title="See the authenticated user's list of media they've liked.">
                <input type="radio" value="liked" name="display_your_images" <?php echo ( ($display_your_images == 'liked') ) ? 'checked="checked"' : null; ?>>
                <span>Liked</span>
              </label>
              <br>
              <label title="No">
                <input type="radio" value="hashtag" name="display_your_images" <?php echo ( ($display_your_images == 'hashtag') && $display_your_images ) ? 'checked="checked"' : null; ?>>
                <span>No</span>
              </label>
            </fieldset>
          </td>
        </tr>
        <tr valign="top">
          <th scope="row">Display The Following Hashtags</th>
          <td>
            <fieldset>
              <legend class="screen-reader-text">
                <span>Display The Following Hashtags</span>
              </legend>
              <label title="Yes">
                <input type="radio" value="1" name="option_display_the_following_hashtags" <?php echo ($option_display_the_following_hashtags == '1') ? 'checked="checked"' : null; ?>>
                <span>Yes</span>
              </label>
              <br>
              <label title="No">
                <input type="radio" value="0" name="option_display_the_following_hashtags" <?php echo ( ($option_display_the_following_hashtags == '0') || !$option_display_the_following_hashtags ) ? 'checked="checked"' : null; ?>>
                <span>No</span>
              </label>
              <div id="showHashtags" style="<?php echo ($option_display_the_following_hashtags) ? 'display: block;' : 'display: none;'; ?>">
                <p>
                  <textarea id="display_the_following_hashtags" class="large-text code" cols="50" rows="10" name="display_the_following_hashtags"><?php echo isset($data['display_the_following_hashtags']) ? $data['display_the_following_hashtags'] : null; ?></textarea>
                </p>
                <p class="description">Hashtags separated by comma, e.g. <em style="font-style:normal; color: #464646;">#buildings, #graffiti, #art</em> etc.</p>
              </div>
            </fieldset>
          </td>
        </tr>
      </tbody>
    </table>
    <p class="submit">
      <?php if ( isset($data['access_token']) && isset($data['user']) ): ?>
        <input id="submit" class="button-primary" type="submit" value="Save Changes" name="submit">&nbsp;
        <a href="<?php echo YINSTAGRAM_PLUGIN_URL . '/admin/posteddata-settings.php?logout=1'; ?>" class="button-primary">Logout</a>
      <?php else: ?>
        <input id="submit" class="button-primary" type="submit" value="Save and Connect" name="submit">
      <?php endif; ?>
    </p>
  </form>
</div>
