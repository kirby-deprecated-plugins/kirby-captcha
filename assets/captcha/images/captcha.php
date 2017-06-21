<?php
  // Adapted for The Art of Web: http://www.the-art-of-web.com/php/captcha/
  // Please acknowledge use of this code by including this header.

  // initialise image with dimensions of 160 x 45 pixels
  $image = @imagecreatetruecolor(175, 45) or die("Cannot Initialize new GD image stream");

  // set background and allocate drawing colours
  $background = imagecolorallocate($image, 0x8D, 0xAE, 0x28);
  imagefill($image, 0, 0, $background);
  $linecolor = imagecolorallocate($image, 0x6E, 0x88, 0x1F);
  $textcolor1 = imagecolorallocate($image, 0x00, 0x55, 0x00);
  $textcolor2 = imagecolorallocate($image, 0xBD, 0xD5, 0x73);

  // draw random lines on canvas
  for($i=0; $i < 8; $i++) {
    imagesetthickness($image, rand(1,3));
    imageline($image, rand(0,160), 0, rand(0,160), 45, $linecolor);
  }

  session_start();

  // using a mixture of TTF fonts
  $fonts = array();
  $fonts[] = '../fonts/font_1.ttf';
  $fonts[] = '../fonts/font_2.ttf';
  $fonts[] = '../fonts/font_3.ttf';

  // add random digits to canvas using random black/white colour
  $digit = '';
  for($x = 10; $x <= 160; $x += 20) {
    $textcolor = (rand() % 2) ? $textcolor1 : $textcolor2;
    $digit .= ($num = rand(0, 9));
    imagettftext($image, 20, rand(-30,30), $x, rand(20, 42), $textcolor, $fonts[array_rand($fonts)], $num);
  }

  // record digits in session variable
  $_SESSION['digit'] = $digit;

  // display image and clean up
  header('Content-type: image/png');
  imagepng($image);
  imagedestroy($image);
?>