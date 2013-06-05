<div class="wrap">
	<div id="icon-edit" class="icon32 icon32-posts-quote"><br></div><h2>Display Options</h2>
  <?php if ($response): ?>
    <div id="message" class="<?php echo $response['class']; ?>">
      <p><?php echo $response['msg']; ?></p>
    </div>
  <?php endif; ?>
  <form action="" method="post" enctype="multipart/form-data">
    <input type="hidden" name="update_display_options" value="Y" />
    <table class="form-table">
      <tbody>
        <tr valign="top">
          <th scope="row">
            <label for="height">Height</label>
          </th>
          <td>
            <input type="text" name="ydo[height]" id="height" class="regular-text" value="<?php echo (isset($data['height'])) ? $data['height'] : '300'; ?>"/>
            <p class="description">In pixel.</p>
          </td>
        </tr>
        <tr valign="top">
          <th scope="row">
            <label for="frame_rate">Frame Rate</label>
          </th>
          <td>
            <input type="text" name="ydo[frame_rate]" id="frame_rate" class="regular-text" value="<?php echo (isset($data['frame_rate'])) ? $data['frame_rate'] : '24'; ?>"/>
            <p class="description">Number of movements/frames per second. Default is 24.</p>
          </td>
        </tr>
        <tr valign="top">
          <th scope="row">
            <label for="speed">Speed</label>
          </th>
          <td>
            <input type="text" name="ydo[speed]" id="speed" class="regular-text" value="<?php echo (isset($data['speed'])) ? $data['speed'] : '1'; ?>"/>
            <p class="description">Number of pixels moved per frame, must be divisible by total height of scroller.  Default is 1.</p>
          </td>
        </tr>
        <tr valign="top">
          <th scope="row">
            <label for="direction">Direction</label>
          </th>
          <td>
            <select id="direction" name="ydo[direction]">
              <option value="forwards" <?php echo (($data['direction'] == 'forwards') || !isset($data['direction'])) ? 'selected="selected"' : null; ?>>Up&nbsp;</option>
              <option value="backwards" <?php echo ($data['direction'] == 'backwards') ? 'selected="selected"' : null; ?>>Down&nbsp;</option>
            </select>
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
                <input type="radio" value="1" name="ydo[display_social_links]" <?php echo ($data['display_social_links']) ? 'checked="checked"' : null; ?>>
                <span>Yes</span>
              </label>
              <br>
              <label title="No">
                <input type="radio" value="0" name="ydo[display_social_links]" <?php echo ($data['display_social_links']) ? null : 'checked="checked"'; ?>>
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
