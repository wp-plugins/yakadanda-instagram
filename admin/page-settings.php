<div class="wrap">
	<div id="icon-edit" class="icon32 icon32-posts-quote"><br></div><h2>Settings</h2>
  <form action="<?php echo YINSTAGRAM_PLUGIN_URL . '/admin/posteddata-settings.php'; ?>" method="post" enctype="multipart/form-data">
    <table class="form-table">
      <tbody>
        <tr valign="top">
          <th scope="row">
            <label for="client_id">Client ID</label>
          </th>
          <td>
            <input type="text" name="client_id" id="client_id" class="regular-text" value="<?php echo $client_id; ?>"/>&nbsp;
            <?php if ( get_option('yinstagram_access_token') ): ?>
              <strong style="color: green;" title="<?php echo $data['full_name']; ?>">Connected, logged as <?php echo $data['username']; ?>.</strong>
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
            <input type="text" name="client_secret" id="client_secret" class="regular-text" value="<?php echo $client_secret; ?>"/>
            <p class="description">Your Instagram Client Secret.</p>
          </td>
        </tr>
        <tr valign="top">
          <th scope="row">Display Your Images</th>
          <td>
            <fieldset>
              <legend class="screen-reader-text">
                <span>Display Your Images</span>
              </legend>
              <label title="Get the most recent media published by a user.">
                <input type="radio" value="recent" name="display_your_images" <?php echo ( ($data['display_your_images'] == 'recent') || !isset($data['display_your_images']) ) ? 'checked="checked"' : null; ?>>
                <span>Recent</span>
              </label>
              <br>
              <label title="See the authenticated user's feed.">
                <input type="radio" value="feed" name="display_your_images" <?php echo ( ($data['display_your_images'] == 'feed') ) ? 'checked="checked"' : null; ?>>
                <span>Feed</span>
              </label>
              <br>
              <label title="See the authenticated user's list of media they've liked.">
                <input type="radio" value="liked" name="display_your_images" <?php echo ( ($data['display_your_images'] == 'liked') ) ? 'checked="checked"' : null; ?>>
                <span>Liked</span>
              </label>
              <br>
              <label title="No">
                <input type="radio" value="0" name="display_your_images" <?php echo ( ($data['display_your_images'] == '0') && isset($data['display_your_images']) ) ? 'checked="checked"' : null; ?>>
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
                <input type="radio" value="1" name="option_display_the_following_hashtags" <?php echo ($data['option_display_the_following_hashtags'] == '1') ? 'checked="checked"' : null; ?>>
                <span>Yes</span>
              </label>
              <br>
              <label title="No">
                <input type="radio" value="0" name="option_display_the_following_hashtags" <?php echo ( ($data['option_display_the_following_hashtags'] == '0') || !isset($data['option_display_the_following_hashtags']) ) ? 'checked="checked"' : null; ?>>
                <span>No</span>
              </label>
              <div id="showHashtags" style="<?php echo ($data['option_display_the_following_hashtags']) ? 'display: block;' : 'display: none;'; ?>">
                <p>
                  <textarea id="display_the_following_hashtags" class="large-text code" cols="50" rows="10" name="display_the_following_hashtags"><?php echo ($data['display_the_following_hashtags']) ? $data['display_the_following_hashtags'] : null; ?></textarea>
                </p>
                <p class="description">Hashtags separated by comma, e.g. <em style="font-style:normal; color: #464646;">#buildings, #graffiti, #art</em> etc.</p>
              </div>
            </fieldset>
          </td>
        </tr>
      </tbody>
    </table>
    <p class="submit">
      <?php if ( get_option('yinstagram_access_token') ): ?>
        <input id="submit" class="button-primary" type="submit" value="Save Changes" name="submit">&nbsp;
        <a href="<?php echo YINSTAGRAM_PLUGIN_URL . '/admin/posteddata-settings.php?logout=1'; ?>" class="button-primary">Logout</a>
      <?php else: ?>
        <input id="submit" class="button-primary" type="submit" value="Save and Connect" name="submit">
      <?php endif; ?>
    </p>
    <p class="description">
      <a href="<?php echo YINSTAGRAM_PLUGIN_URL . '/manual.php'; ?>" target="_blank">See these instructions</a> to register Instagram clients.
    </p>
    <h3>Shortcode Examples:</h3> 
    <ul class="sc_examples">
	    <li>[yinstagram]</li>
    </ul>
  </form>
</div>
