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
        <!-- <tr valign="top">
          <th scope="row">
            <label for="logo_display_file">Logo Display</label>
          </th>
          <td>
            <?php //if ($data): ?>
              <p><img alt="Logo" src="<?php //echo $data['logo_display_file']; ?>"/></p>
            <?php //endif; ?>
            <input type="file" name="logo_display_file" id="logo_display_file" />
            <p class="description">Upload logo 100x100 maximum.</p>
          </td>
        </tr> -->
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
        <!-- <tr valign="top">
          <th scope="row">
            <label for="resolution">Resolution</label>
          </th>
          <td>
            <select id="resolution" name="resolution">
              <option value="thumbnail" <?php //echo (($data['resolution'] == 'thumbnail') || !isset($data['resolution'])) ? 'selected="selected"' : null; ?>>Thumbnail (150x150)&nbsp;</option>
              <option value="low" <?php //echo ($data['resolution'] == 'low') ? 'selected="selected"' : null; ?>>Low (306x306)&nbsp;</option>
              <option value="standard" <?php //echo ($data['resolution'] == 'standard') ? 'selected="selected"' : null; ?>>Standard (612x612)&nbsp;</option>
            </select>
          </td>
        </tr> -->
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
      </tbody>
    </table>
    <p class="submit">
      <input id="submit" class="button-primary" type="submit" value="<?php echo ($data) ? 'Save Changes' : 'Save'; ?>" name="submit">
    </p>
  </form>
</div>
