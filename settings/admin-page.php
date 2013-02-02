<div class="wrap">
	<div id="icon-edit" class="icon32 icon32-posts-quote"><br></div><h2>Settings</h2>
  <?php if (($_REQUEST['action'] == 'create') || ($_REQUEST['action'] == 'update')): ?>
    <div id="message" class="<?php echo $response['class']; ?>">
      <p><?php echo $response['message']; ?></p>
    </div>
  <?php endif; ?>
  <form action="<?php echo get_admin_url('','admin.php?page=settings/admin-page.php&action='.$action); ?>" method="post" enctype="multipart/form-data">
    <?php if($data): ?>
      <input type="hidden" name="id" id="id" value="<?php echo $data['ID']; ?>"/>
    <?php endif; ?>
    <table class="form-table">
      <tbody>
        <tr valign="top">
          <th scope="row">
            <label for="client_id">Client ID</label>
          </th>
          <td>
            <input type="text" name="client_id" id="client_id" class="regular-text" value="<?php echo ($data['client_id']) ? $data['client_id'] : null; ?>"/>
            <p class="description">Your Instagram Client Id.</p>
          </td>
          <td rowspan="4">
            <?php if ( $data['access_token'] ) : ?>
              <p><label>Logged as:&nbsp;</label><?php echo $data['username']; ?></p>
              <p><img alt="<?php echo $data['username']; ?>" src="<?php echo $data['picture']; ?>" height="50" width="50"/></p>
            <?php else: ?>
              <p><!-- message here --></p>
            <?php endif; ?>
          </td>
        </tr>
        <tr valign="top">
          <th scope="row">
            <label for="client_secret">Client Secret</label>
          </th>
          <td>
            <input type="text" name="client_secret" id="client_secret" class="regular-text" value="<?php echo ($data['client_secret']) ? $data['client_secret'] : null; ?>"/>
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
              <label title="Yes">
                <input type="radio" value="1" name="display_your_images" <?php echo ($data['display_your_images']) ? 'checked="checked"' : null; ?>>
                <span>Yes</span>
              </label>
              <br>
              <label title="No">
                <input type="radio" value="0" name="display_your_images" <?php echo ($data['display_your_images']) ? null : 'checked="checked"'; ?>>
                <span>No</span>
              </label>
            </fieldset>
          </td>
        </tr>
        <tr valign="top">
          <th scope="row">Display The Following Hashtags</th>
          <td colspan="2">
            <fieldset>
              <legend class="screen-reader-text">
                <span>Display The Following Hashtags</span>
              </legend>
              <label title="Yes">
                <input type="radio" value="1" name="option_display_the_following_hashtags" <?php echo ($data['display_the_following_hashtags']) ? 'checked="checked"' : null; ?>>
                <span>Yes</span>
              </label>
              <br>
              <label title="No">
                <input type="radio" value="0" name="option_display_the_following_hashtags" <?php echo ($data['display_the_following_hashtags']) ? null : 'checked="checked"'; ?>>
                <span>No</span>
              </label>
              <div id="showHashtags" style="<?php echo ($data['display_the_following_hashtags']) ? 'display: block;' : 'display: none;'; ?>">
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
      <input id="submit" class="button-primary" type="submit" value="<?php echo ($data) ? 'Save Changes' : 'Save'; ?>" name="submit">
    </p>
    <p class="description">
      <a href="<?php echo YAKADANDA_INSTAGRAM_PLUGIN_URL . '/manual.php'; ?>" onclick="window.open(this.href, Math.random(), 'height=500,width=600'); return false">See these instructions</a> to register Instagram clients. You need to allow pop-ups for this page to have this functionality work.
    </p>
    <h3>Shortcode Examples:</h3> 
    <ul class="sc_examples">
	    <li>[yinstagram]</li>
    </ul>
  </form>
</div>
