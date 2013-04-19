<?php require_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/wp-load.php'); ?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>How to get your Instagram Client ID and Client Secret</title>
    <meta name="description" content="How to get your Instagram Client ID and Client Secret">
    <meta name="viewport" content="width=device-width">
  </head>
  <body>
    <!--[if lt IE 7]>
        <p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
    <![endif]-->
    <h1>How to get your Instagram Client ID and Client Secret</h1>
    <ol>
      <li>
        Login in <a href="https://instagram.com/accounts/login/?next=" target="_blank">https://instagram.com/accounts/login/?next=</a> and then visit api link in <a href="http://instagram.com/developer" target="_blank">http://instagram.com/developer</a>
      </li>
      <li>
        Click on the Manage Clients link.
        <br>
        <img src="img/manual-1.png"/>
      </li>
      <li>
        Register you applications by click the Register a New Client button.
        <br>
        <img src="img/manual-2.png"/>
      </li>
      <li>
        Fill in the register new OAuth Client form with:
        <dl>
          <dt><strong>Application name</strong></dt>
          <dd><em>Name of your website.</em></dd>
          <dt><strong>Description</strong></dt>
          <dd><em>Yakadanda Instagram wordpress plugin</em></dd>
          <dt><strong>Website</strong></dt>
          <dd><em>Your website url</em></dd>
          <dt><strong>OAuth redirect_url</strong></dt>
          <dd><em><?php echo YAKADANDA_INSTAGRAM_PLUGIN_URL . '/authentication.php'; ?></em></dd>
        </dl>
        <br>
        <img src="img/3.png">
      </li>
    </ol>
  </body>
</html>
