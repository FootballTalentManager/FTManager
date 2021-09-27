<?php
$fontName = "Vera.ttf";
$fontCaptcha = "font/fft/" . $fontName;

session_start();
$captchaValue = @substr(md5(getRandomWord(9)), 0, 9);
$_SESSION['captchaValue'] = $captchaValue;

$imageCaptcha = ImageCreateFromPNG("img/Captcha/fundocaptch.png");

$colorCaptchaRed = ImageColorAllocate($imageCaptcha, 203, 67, 53);
$colorCaptchaBlue = ImageColorAllocate($imageCaptcha, 41, 128, 185);

$code1 = substr($captchaValue, 0, 4);
$code2 = substr($captchaValue, 4, 9);

//Primeira metade do captcha
ImageTTFText(
    $imageCaptcha,      // Image
    20,            // Font size
    -5,           // Font angle
    40,              // X position
    30,              // Y position
    $colorCaptchaRed,   // Font color
    $fontCaptcha,       // Font type
    $code1              // Text to write
);

//Segunda metade do captcha
ImageTTFText(
    $imageCaptcha,      // Image
    20,            // Font size
    5,            // Font angle
    120,             // X position
    30,              // Y position
    $colorCaptchaBlue,  // Font color
    $fontCaptcha,       // Font type
    $code2              // Text to write
);

header( "Content-type: image/png" );
ImagePNG( $imageCaptcha);
ImageDestroy( $imageCaptcha );


function getRandomWord($len = 10) {
    $word = array_merge(range('a', 'z'), range('A', 'Z'));
    shuffle($word);
    return substr(implode($word), 0, $len);
}