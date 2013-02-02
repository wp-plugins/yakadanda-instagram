<div class="wrap">
	<div id="icon-edit" class="icon32 icon32-posts-quote"><br></div><h2>Display Options</h2>
  <?php if (($_REQUEST['action'] == 'create') || ($_REQUEST['action'] == 'update')): ?>
    <div id="message" class="<?php echo $response['class']; ?>">
      <p><?php echo $response['message']; ?></p>
    </div>
  <?php endif; ?>
  <form action="<?php echo get_admin_url('','admin.php?page=display-options/admin-page.php&action='.$action); ?>" method="post" enctype="multipart/form-data">
    <?php if($data): ?>
      <input type="hidden" name="id" id="id" value="<?php echo $data['ID']; ?>"/>
    <?php endif; ?>
    <table class="form-table">
      <tbody>
        <tr valign="top">
          <th scope="row">
            <label for="header_menu_color">Header Menu Color</label>
          </th>
          <td>
            <input type="text" name="header_menu_color" id="header_menu_color" class="regular-text colorwell" value="<?php echo ($data['header_menu_color']) ? $data['header_menu_color'] : '#000000'; ?>"/>
          </td>
          <td rowspan="6">
            <div id="picker" style="float: left;"></div>
          </td>
        </tr>
        <tr valign="top">
          <th scope="row">
            <label for="background_color">Background Color</label>
          </th>
          <td>
            <input type="text" name="background_color" id="background_color" class="regular-text colorwell" value="<?php echo ($data['background_color']) ? $data['background_color'] : '#000000'; ?>"/>
          </td>
        </tr>
        <tr valign="top">
          <th scope="row">
            <label for="height">Height</label>
          </th>
          <td>
            <input type="text" name="height" id="height" class="regular-text" value="<?php echo ($data['height']) ? $data['height'] : '300'; ?>"/>
            <p class="description">In pixel.</p>
          </td>
        </tr>
        <tr valign="top">
          <th scope="row">
            <label for="direction">Direction</label>
          </th>
          <td>
            <select id="direction" name="direction">
              <option value="up" <?php echo (($data['direction'] == 'up') || !isset($data['direction'])) ? 'selected="selected"' : null; ?>>Up&nbsp;</option>
              <option value="down" <?php echo ($data['direction'] == 'down') ? 'selected="selected"' : null; ?>>Down&nbsp;</option>
            </select>
          </td>
        </tr>
        <tr valign="top">
          <th scope="row">
            <label for="speed">Speed</label>
          </th>
          <td>
            <input type="text" name="speed" id="speed" class="regular-text" value="<?php echo ($data['speed']) ? $data['speed'] : '10000'; ?>"/>
            <p class="description">In millisecond.</p>
          </td>
        </tr>
        <tr valign="top">
          <th scope="row">Social Links</th>
          <td>
            <fieldset>
              <legend class="screen-reader-text">
                <span>Social Links</span>
              </legend>
              <label title="Yes">
                <input type="radio" value="1" name="display_social_links" <?php echo ($data['display_social_links']) ? 'checked="checked"' : null; ?>>
                <span>Yes</span>
              </label>
              <br>
              <label title="No">
                <input type="radio" value="0" name="display_social_links" <?php echo ($data['display_social_links']) ? null : 'checked="checked"'; ?>>
                <span>No</span>
              </label>
            </fieldset>
          </td>
        </tr>
      </tbody>
    </table>
    <p class="submit">
      <input id="submit" class="button-primary" type="submit" value="<?php echo ($data) ? 'Save Changes' : 'Save'; ?>" name="submit">
    </p>
  </form>
</div>
