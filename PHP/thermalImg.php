<?php
include_once 'ThermoCam.php';
$ThermoCam = new ThermoCam('gradients/gradient-bry.png');
$widthImgT = (int) $_GET['width'];
$data = 'data/' . $_GET['data'];
$ThermoCam->getImgT($data, $widthImgT);
?>



