<?php

/////////////////////////////////////////
// VALIDATE CAPTCHA
/////////////////////////////////////////

  if($_POST)
    {
  session_start();
  $captcha = true;

    if($_POST['captcha'] != $_SESSION['digit'])
      {
    $captcha = false;
      }
    else
      {
    $encrypted = SaferCrypto::encrypt($timestamp,$kirbyEncryptDecrypt,true);
    setcookie('captcha',$encrypted);
    header('Location: '.$_SERVER['PHP_SELF']);
    exit();
      }

  session_destroy();
    }

  if(!$_POST || !$captcha):

?><!DOCTYPE html>

<html>

<head>
  <title>Captcha</title>

  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="robots" content="noindex, nofollow" />

  <link rel="shortcut icon" href="../assets/captcha/images/favicon.ico"/>
  <link rel="stylesheet" href="assets/css/panel.min.css" />
  <link rel="stylesheet" href="../assets/captcha/css/style.css" />
</head>

<body class="login grey ltr">

<div class="modal-content">

  <form method="post" action="#" onsubmit="return checkForm(this);" id="captchaForm" class="form">

    <h4 id="captchaError"<?php if($_POST && !$captcha): ?> class="show"<?php endif; ?>>
      <b>Validation error, try again.</b>
      <i>Prove you're not a robot.</i>
    </h4>

    <hr>

    <img id="captcha" src="../assets/captcha/images/captcha.php" width="175" height="45" border="1" alt="CAPTCHA">
    <a onclick="refreshCaptcha();" title="click to refresh the image" class="btn btn-rounded btn-submit">refresh</a>

    <fieldset class="fieldset field-grid cf">
      <div class="field field-grid-item field-with-icon">
        <label class="label" for="form-field-captcha">Captcha<abbr title="Required">*</abbr></label>
        <div class="field-content">
          <input class="input" type="text" name="captcha" autocomplete="off" id="captchaCode" maxlength="8" onkeyup="checkCaptcha(this);">
          <div class="field-icon">
            <i class="icon fa fa-lock"></i>
          </div>
        </div>
      <small>(enter the digits, press <strong>enter</strong>).</small>
      </div>
    </fieldset>

  </form>

</div>

<script src="assets/js/dist/panel.min.js"></script>
<script src="../assets/captcha/js/script.js"></script>
<script>(function(){$('.modal-content').center(48).css({opacity:1});})();</script>

</body>
</html>

<?php endif ?>