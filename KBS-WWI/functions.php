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
    $randomGetal = rand(0, 0);
    $img = "https://picsum.photos/id/$randomGetal/1000/800";
    // $smallImg = imagejpeg($img, NULL , 20);
    return $img;
}
