<div class="wrap">
  <div id="icon-edit" class="icon32 icon32-posts-quote"><br></div><h2><?php _e('Display Options' ,'yakadanda-instagram'); ?></h2>
  <?php if ($message): ?>
    <div id="yinstagram-msg" class="<?php echo $message['class']; ?>">
      <p><?php echo $message['msg']; ?></p>
    </div>
  <?php if (isset($message['cookie'])) setcookie('yinstagram_response', null, time()-1, '/'); endif; ?>
  <form action="" method="post" enctype="multipart/form-data">
    <input type="hidden" name="update_display_options" value="1" />
    <table class="form-table">
      <tbody>
        <tr valign="top">
          <th scope="row">
            <label for="scroll"><?php _e('Scroll' ,'yakadanda-instagram'); ?></label>
          </th>
          <td>
            <select id="scroll" name="ydo[scroll]">
              <option value="auto" <?php echo ($data['scroll'] == 'auto') ? 'selected="selected"' : null; ?>><?php _e('Auto' ,'yakadanda-instagram'); ?>&nbsp;</option>
              <option value="infinite" <?php echo ($data['scroll'] == 'infinite') ? 'selected="selected"' : null; ?>><?php _e('Infinite' ,'yakadanda-instagram'); ?>&nbsp;</option>
            </select>
          </td>
        </tr>
        <tr valign="top" style="<?php echo ( $data['scroll'] == 'infinite' ) ? 'display: none;' : ''; ?>">
          <th scope="row">
            <label for="height"><?php _e('Height' ,'yakadanda-instagram'); ?></label>
          </th>
          <td>
            <input type="text" name="ydo[height]" id="height" class="regular-text" value="<?php echo (isset($data['height'])) ? $data['height'] : '300'; ?>"/>
            <p class="description"><?php _e('In pixel.' ,'yakadanda-instagram'); ?></p>
          </td>
        </tr>
        <tr valign="top" style="<?php echo ( $data['scroll'] == 'infinite' ) ? 'display: none;' : ''; ?>">
          <th scope="row">
            <label for="frame_rate"><?php _e('Frame Rate' ,'yakadanda-instagram'); ?></label>
          </th>
          <td>
            <input type="text" name="ydo[frame_rate]" id="frame_rate" class="regular-text" value="<?php echo (isset($data['frame_rate'])) ? $data['frame_rate'] : '24'; ?>"/>
            <p class="description"><?php _e('Number of movements/frames per second. Default is 24.' ,'yakadanda-instagram'); ?></p>
          </td>
        </tr>
        <tr valign="top" style="<?php echo ( $data['scroll'] == 'infinite' ) ? 'display: none;' : ''; ?>">
          <th scope="row">
            <label for="speed"><?php _e('Speed' ,'yakadanda-instagram'); ?></label>
          </th>
          <td>
            <input type="text" name="ydo[speed]" id="speed" class="regular-text" value="<?php echo (isset($data['speed'])) ? $data['speed'] : '1'; ?>"/>
            <p class="description"><?php _e('Number of pixels moved per frame, must be divisible by total height of scroller.  Default is 1.' ,'yakadanda-instagram'); ?></p>
          </td>
        </tr>
        <tr valign="top" style="<?php echo ( $data['scroll'] == 'infinite' ) ? 'display: none;' : ''; ?>">
          <th scope="row">
            <label for="direction"><?php _e('Direction' ,'yakadanda-instagram'); ?></label>
          </th>
          <td>
            <select id="direction" name="ydo[direction]">
              <option value="forwards" <?php echo ($data['direction'] == 'forwards') ? 'selected="selected"' : null; ?>><?php _e('Up' ,'yakadanda-instagram'); ?>&nbsp;</option>
              <option value="backwards" <?php echo ($data['direction'] == 'backwards') ? 'selected="selected"' : null; ?>><?php _e('Down' ,'yakadanda-instagram'); ?>&nbsp;</option>
            </select>
          </td>
        </tr>
        <tr valign="top">
          <th scope="row"><label for="tooltip"><?php _e('Tooltip' ,'yakadanda-instagram'); ?></label></th>
          <td>
            <input type="checkbox" id="tooltip" name="ydo[tooltip]" <?php echo ($data['tooltip'] == 'on') ? 'checked="checked"' : null; ?>><label for="tooltip">qtip2</label>
          </td>
        </tr>
        <tr valign="top">
          <th scope="row"><?php _e('Lightbox' ,'yakadanda-instagram'); ?></th>
          <td>
            <select id="lightbox" name="ydo[lightbox]">
              <option value="disable" <?php echo ($data['lightbox'] == 'disable') ? 'selected="selected"' : null; ?>><?php _e('Disable' ,'yakadanda-instagram'); ?>&nbsp;</option>
              <option value="thickbox" <?php echo ($data['lightbox'] == 'thickbox') ? 'selected="selected"' : null; ?>>ThickBox&nbsp;</option>
              <option value="colorbox" <?php echo ($data['lightbox'] == 'colorbox') ? 'selected="selected"' : null; ?>>Colorbox&nbsp;</option>
            </select>
            <p class="description"><?php _e('Only for images widget and infinite scroll.' ,'yakadanda-instagram'); ?></p>
          </td>
        </tr>
        <tr valign="top" style="<?php echo ( $data['lightbox'] == 'colorbox' ) ? '' : 'display: none;'; ?>">
          <th scope="row">
            <label for="theme"><?php _e('Theme' ,'yakadanda-instagram'); ?></label>
          </th>
          <td>
            <select id="theme" name="ydo[theme]">
              <option value="1" <?php echo ($data['theme'] == '1') ? 'selected="selected"' : null; ?>>1&nbsp;</option>
              <option value="2" <?php echo ($data['theme'] == '2') ? 'selected="selected"' : null; ?>>2&nbsp;</option>
              <option value="3" <?php echo ($data['theme'] == '3') ? 'selected="selected"' : null; ?>>3&nbsp;</option>
              <option value="4" <?php echo ($data['theme'] == '4') ? 'selected="selected"' : null; ?>>4&nbsp;</option>
              <option value="5" <?php echo ($data['theme'] == '5') ? 'selected="selected"' : null; ?>>5&nbsp;</option>
            </select>
          </td>
        </tr>
        <tr valign="top" style="<?php echo ( $data['lightbox'] == 'colorbox' ) ? '' : 'display: none;'; ?>">
          <th scope="row">
            <label for="effect"><?php _e('Effect' ,'yakadanda-instagram'); ?></label>
          </th>
          <td>
            <select id="effect" name="ydo[effect]">
              <option value="elastic" <?php echo ($data['effect'] == 'elastic') ? 'selected="selected"' : null; ?>><?php _e('Elastic' ,'yakadanda-instagram'); ?>&nbsp;</option>
              <option value="fade" <?php echo ($data['effect'] == 'fade') ? 'selected="selected"' : null; ?>><?php _e('Fade' ,'yakadanda-instagram'); ?>&nbsp;</option>
              <option value="slideshow" <?php echo ($data['effect'] == 'slideshow') ? 'selected="selected"' : null; ?>><?php _e('Slideshow' ,'yakadanda-instagram'); ?>&nbsp;</option>
            </select>
          </td>
        </tr>
        <tr valign="top">
          <th scope="row"><?php _e('Social Links' ,'yakadanda-instagram'); ?></th>
          <td>
            <fieldset>
              <legend class="screen-reader-text">
                <span><?php _e('Social Links' ,'yakadanda-instagram'); ?></span>
              </legend>
              <label title="Yes">
                <input type="radio" value="1" name="ydo[display_social_links]" <?php echo ($data['display_social_links']) ? 'checked="checked"' : null; ?>>
                <span><?php _e('Yes' ,'yakadanda-instagram'); ?></span>
              </label>
              <br>
              <label title="No">
                <input type="radio" value="0" name="ydo[display_social_links]" <?php echo ($data['display_social_links']) ? null : 'checked="checked"'; ?>>
                <span><?php _e('No' ,'yakadanda-instagram'); ?></span>
              </label>
            </fieldset>
          </td>
        </tr>
        <tr valign="top">
          <th scope="row">
            <label for="order"><?php _e('Order' ,'yakadanda-instagram'); ?></label>
          </th>
          <td>
            <select id="order" name="ydo[order]">
              <option value="default" <?php echo ($data['order'] == 'default') ? 'selected="selected"' : null; ?>><?php _e('Default' ,'yakadanda-instagram'); ?>&nbsp;</option>
              <option value="shuffle" <?php echo ($data['order'] == 'shuffle') ? 'selected="selected"' : null; ?>><?php _e('Shuffle' ,'yakadanda-instagram'); ?>&nbsp;</option>
            </select>
          </td>
        </tr>
      </tbody>
    </table>
    <p class="submit">
      <input id="submit" class="button-primary" type="submit" value="<?php echo ($data) ? __('Save Changes', 'yakadanda-instagram') : __('Save', 'yakadanda-instagram'); ?>" name="submit">&nbsp;
      <input id="yinstagram-restore-display-options" class="button-primary" type="button" value="<?php _e('Reset', 'yakadanda-instagram'); ?>" name="yinstagram-restore-display-options">
    </p>
  </form>
</div>
