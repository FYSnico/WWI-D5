<?php
function check2Empty($arg1, $arg2)
{
    if (empty($arg1) || empty($arg2)) {
        return true;
    } else {
        return false;
    }
}

function randomPicture()
{
    $randomGetal = rand(0, 100);
    $img = "https://picsum.photos/id/$randomGetal/350/275";
    // $smallImg = imagejpeg($img, NULL , 20);
    return $img;
}
//// Create a blank image and add some text
//$im = imagecreatetruecolor(120, 20);
//$text_color = imagecolorallocate($im, 233, 14, 91);
//imagestring($im, 1, 5, 5, 'A Simple Text String', $text_color);
//
//// Set the content type header - in this case image/jpeg
//header('Content-Type: image/jpeg');
//
//// Skip the to parameter using NULL, then set the quality to 75%
//imagejpeg($im, NULL, 75);
//imagejp
//
//// Free up memory
//imagedestroy($im);
