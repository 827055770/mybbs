<?php
function getSm($filename)
{
    $arr = explode('/',$filename);
    $arr[3] = 'sm_'.$arr[3];
    return implode('/',$arr);
}