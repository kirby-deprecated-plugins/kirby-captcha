function checkForm(form) {

  if(!form.captcha.value.match(/^\d{8}$/)) {
    document.getElementById('captchaError').setAttribute("class", "show");
    form.captcha.focus();
    return false;
  }

  return true;
}

function refreshCaptcha() {
  document.getElementById('captcha').src = '../assets/captcha/images/captcha.php?' + Math.random();
  document.getElementById('captchaCode').value = '';
  document.getElementById('captchaCode').focus();
  return false;
}

function checkCaptcha(which) {
  which.value = which.value.replace(/[^\d]+/g, '');
}

document.getElementById('captchaCode').focus();